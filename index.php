<?php
// Отправляем браузеру правильную кодировку,
// файл index.php должен быть в кодировке UTF-8 без BOM.
header('Content-Type: text/html; charset=UTF-8');

// В суперглобальном массиве $_SERVER PHP сохраняет некоторые заголовки запроса HTTP
// и другие сведения о клиенте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  // В суперглобальном массиве $_GET PHP хранит все параметры, переданные в текущем запросе через URL.
  if (!empty($_GET['save'])) {
    // Если есть параметр save, то выводим сообщение пользователю.
    print('Спасибо, результаты сохранены.');
  }
  // Включаем содержимое файла form.php.
  include('form.php');
  // Завершаем работу скрипта.
  exit();
}
// Иначе, если запрос был методом POST, т.е. нужно проверить данные и сохранить их в БД.

// Проверяем ошибки.
$errors = FALSE;

// 1. Проверка ФИО (только буквы, пробелы, дефисы)
if (empty($_POST['full_name'])) {
    print('Заполните ФИО.<br/>');
    $errors = TRUE;
} elseif (strlen($_POST['full_name']) > 150) {
    print('ФИО не должно превышать 150 символов.<br/>');
    $errors = TRUE;
} elseif (!preg_match('/^[a-zA-Zа-яА-ЯёЁ\s\-]+$/u', $_POST['full_name'])) {
    print('ФИО должно содержать только буквы, пробелы и дефисы.<br/>');
    $errors = TRUE;
}

// 2. Проверка телефона (только цифры, пробелы, +, (, ), -)
if (empty($_POST['phone'])) {
    print('Заполните телефон.<br/>');
    $errors = TRUE;
} elseif (!preg_match('/^[\d\s\+\(\)-]+$/', $_POST['phone'])) {
    print('Телефон должен содержать только цифры, пробелы, +, (, ), -.<br/>');
    $errors = TRUE;
}

// 3. Проверка email (должен содержать @)
if (empty($_POST['email'])) {
    print('Заполните email.<br/>');
    $errors = TRUE;
} elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    print('Введите корректный email с символом @.<br/>');
    $errors = TRUE;
}

// 4. Проверка даты рождения
if (empty($_POST['birth_date'])) {
    print('Заполните дату рождения.<br/>');
    $errors = TRUE;
} elseif (!strtotime($_POST['birth_date'])) {
    print('Неверный формат даты.<br/>');
    $errors = TRUE;
}

// 5. Проверка пола
if (empty($_POST['gender']) || !in_array($_POST['gender'], ['male', 'female'])) {
    print('Выберите пол.<br/>');
    $errors = TRUE;
}

// 6. Проверка языков программирования
$allowed_langs = ['Pascal', 'C', 'C++', 'JavaScript', 'PHP', 'Python', 'Java', 'Haskell', 'Clojure', 'Prolog', 'Scala', 'Go'];
if (empty($_POST['languages'])) {
    print('Выберите хотя бы один язык программирования.<br/>');
    $errors = TRUE;
} else {
    foreach ($_POST['languages'] as $lang) {
        if (!in_array($lang, $allowed_langs)) {
            print("Недопустимый язык: $lang<br/>");
            $errors = TRUE;
            break;
        }
    }
}

// 7. Проверка биографии (необязательно, но если есть - проверяем длину)
if (!empty($_POST['biography']) && strlen($_POST['biography']) > 5000) {
    print('Биография не должна превышать 5000 символов.<br/>');
    $errors = TRUE;
}

// 8. Проверка чекбокса согласия
if (empty($_POST['agreed'])) {
    print('Вы должны ознакомиться с контрактом.<br/>');
    $errors = TRUE;
}

if ($errors) {
    // При наличии ошибок завершаем работу скрипта.
    exit();
}

// Сохранение в базу данных.

$user = 'u82669';
$pass = '9085380';  // Замените на ваш реальный пароль
$db = new PDO('mysql:host=localhost;dbname=u82669', $user, $pass,
  [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

// Подготовленный запрос.
try {
    // Вставка в таблицу users
    $stmt = $db->prepare("INSERT INTO users (full_name, phone, email, birth_date, gender, biography, agreed) 
                          VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $_POST['full_name'],
        $_POST['phone'],
        $_POST['email'],
        $_POST['birth_date'],
        $_POST['gender'],
        $_POST['biography'] ?? '',
        isset($_POST['agreed']) ? 1 : 0
    ]);
    $user_id = $db->lastInsertId();
    
    // Вставка языков (связь один ко многим)
    $stmt_lang = $db->prepare("SELECT id FROM programming_languages WHERE name = ?");
    $stmt_insert = $db->prepare("INSERT INTO user_languages (user_id, language_id) VALUES (?, ?)");
    
    foreach ($_POST['languages'] as $lang_name) {
        $stmt_lang->execute([$lang_name]);
        $lang_id = $stmt_lang->fetchColumn();
        if ($lang_id) {
            $stmt_insert->execute([$user_id, $lang_id]);
        }
    }
    
} catch(PDOException $e) {
    print('Error : ' . $e->getMessage());
    exit();
}

// Делаем перенаправление.
header('Location: ?save=1');
?>