<?php
header('Content-Type: text/html; charset=UTF-8');

// Обработка POST-запроса
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = [];

    // Валидация полей
    if (empty($_POST['full_name'])) {
        $errors['full_name'] = 'Заполните ФИО.';
    } elseif (strlen($_POST['full_name']) > 150) {
        $errors['full_name'] = 'ФИО не должно превышать 150 символов.';
    } elseif (!preg_match('/^[a-zA-Zа-яА-ЯёЁ\s\-]+$/u', $_POST['full_name'])) {
        $errors['full_name'] = 'ФИО должно содержать только буквы, пробелы и дефисы.';
    }

    if (empty($_POST['phone'])) {
        $errors['phone'] = 'Заполните телефон.';
    } elseif (!preg_match('/^[\d\s\+\(\)-]+$/', $_POST['phone'])) {
        $errors['phone'] = 'Телефон должен содержать только цифры, пробелы, +, (, ), -.';
    }

    if (empty($_POST['email'])) {
        $errors['email'] = 'Заполните email.';
    } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Введите корректный email с символом @.';
    }

    if (empty($_POST['birth_date'])) {
        $errors['birth_date'] = 'Заполните дату рождения.';
    } elseif (!strtotime($_POST['birth_date'])) {
        $errors['birth_date'] = 'Неверный формат даты.';
    }

    if (empty($_POST['gender']) || !in_array($_POST['gender'], ['male', 'female'])) {
        $errors['gender'] = 'Выберите пол.';
    }

    $allowed_langs = ['Pascal', 'C', 'C++', 'JavaScript', 'PHP', 'Python', 'Java', 'Haskell', 'Clojure', 'Prolog', 'Scala', 'Go'];
    if (empty($_POST['languages'])) {
        $errors['languages'] = 'Выберите хотя бы один язык программирования.';
    } else {
        foreach ($_POST['languages'] as $lang) {
            if (!in_array($lang, $allowed_langs)) {
                $errors['languages'] = "Недопустимый язык: $lang";
                break;
            }
        }
    }

    if (!empty($_POST['biography']) && strlen($_POST['biography']) > 5000) {
        $errors['biography'] = 'Биография не должна превышать 5000 символов.';
    }

    if (empty($_POST['agreed'])) {
        $errors['agreed'] = 'Вы должны ознакомиться с контрактом.';
    }

    // Если есть ошибки — выводим их и показываем форму заново
    if (!empty($errors)) {
        include('form.php');
        exit();
    }

    // Сохранение в БД
    $user = 'u82669';
    $pass = '9085380'; 
    try {
        $db = new PDO('mysql:host=localhost;dbname=u82669', $user, $pass,
            [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

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

        $stmt_lang = $db->prepare("SELECT id FROM programming_languages WHERE name = ?");
        $stmt_insert = $db->prepare("INSERT INTO user_languages (user_id, language_id) VALUES (?, ?)");

        foreach ($_POST['languages'] as $lang_name) {
            $stmt_lang->execute([$lang_name]);
            $lang_id = $stmt_lang->fetchColumn();
            if ($lang_id) {
                $stmt_insert->execute([$user_id, $lang_id]);
            }
        }

        // Успех — показываем форму с сообщением
        $success = true;
        include('form.php');
        exit();

    } catch (PDOException $e) {
        die("Ошибка БД: " . $e->getMessage());
    }
    exit();
}

// GET-запрос — показываем форму
$errors = [];
$success = false;
include('form.php');
?>