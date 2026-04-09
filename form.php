<?php
$old_data = $_SESSION['old_data'] ?? [];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрационная форма</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', 'Roboto', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 2rem 1rem;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            animation: fadeIn 0.5s ease;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .header h1 { font-size: 1.8rem; margin-bottom: 0.5rem; font-weight: 600; }
        .header p { font-size: 0.95rem; opacity: 0.9; }
        .content { padding: 2rem; }
        .form-group { margin-bottom: 1.5rem; }
        label { display: block; font-weight: 600; margin-bottom: 0.5rem; color: #333; font-size: 0.9rem; }
        .required::after { content: " *"; color: #e74c3c; }
        input, select, textarea {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            font-family: inherit;
        }
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        .radio-group { display: flex; gap: 1.5rem; margin-top: 0.5rem; }
        .radio-group label { display: inline-flex; align-items: center; gap: 0.5rem; font-weight: normal; cursor: pointer; }
        .radio-group input[type="radio"] { width: 18px; height: 18px; accent-color: #667eea; }
        select[multiple] { min-height: 150px; background: #f8fafc; }
        select[multiple] option { padding: 0.5rem; cursor: pointer; }
        select[multiple] option:checked { background: #667eea; color: white; }
        textarea { resize: vertical; }
        .checkbox-group { margin: 1.5rem 0; }
        .checkbox-group label { display: inline-flex; align-items: center; gap: 0.5rem; font-weight: normal; cursor: pointer; }
        .checkbox-group input[type="checkbox"] { width: 20px; height: 20px; accent-color: #667eea; }
        button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 1rem 2rem;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 12px;
            cursor: pointer;
            width: 100%;
            transition: transform 0.2s;
        }
        button:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3); }
        small { display: block; color: #6c757d; font-size: 0.75rem; margin-top: 0.25rem; }
        .error-list { background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; border: 1px solid #f5c6cb; }
        .success-message { background: #d4edda; color: #155724; padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; border: 1px solid #c3e6cb; }
        @media (max-width: 768px) {
            .content { padding: 1.5rem; }
            .radio-group { flex-direction: column; gap: 0.5rem; }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>📝 Регистрационная форма</h1>
        <p>Заполните все поля для регистрации</p>
    </div>
    <div class="content">

        <?php if (!empty($_GET['save'])): ?>
            <div class="success-message">✅ Спасибо! Ваши данные успешно сохранены.</div>
        <?php endif; ?>

        <?php if (!empty($_SESSION['errors'])): ?>
            <div class="error-list">
                <?php foreach ($_SESSION['errors'] as $error): ?>
                    ⚠️ <?= htmlspecialchars($error) ?><br>
                <?php endforeach; ?>
            </div>
            <?php unset($_SESSION['errors']); ?>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label class="required">ФИО</label>
                <input type="text" name="full_name" required maxlength="150" pattern="[A-Za-zА-Яа-яЁё\s]+" title="Только буквы и пробелы" placeholder="Иванов Иван Иванович" value="<?= htmlspecialchars($old_data['full_name'] ?? '') ?>">
                <small>Только буквы и пробелы, не более 150 символов</small>
            </div>

            <div class="form-group">
                <label class="required">Телефон</label>
                <input type="tel" name="phone" required pattern="[\d\s\+\(\)-]+" title="Только цифры, пробелы, +, (, ), -" placeholder="+7 (123) 456-78-90" value="<?= htmlspecialchars($old_data['phone'] ?? '') ?>">
                <small>Пример: +7 (123) 456-78-90</small>
            </div>

            <div class="form-group">
                <label class="required">E-mail</label>
                <input type="email" name="email" required placeholder="example@mail.ru" value="<?= htmlspecialchars($old_data['email'] ?? '') ?>">
                <small>Должен содержать символ @</small>
            </div>

            <div class="form-group">
                <label class="required">Дата рождения</label>
                <input type="date" name="birth_date" required value="<?= htmlspecialchars($old_data['birth_date'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label class="required">Пол</label>
                <div class="radio-group">
                    <label><input type="radio" name="gender" value="male" <?= (isset($old_data['gender']) && $old_data['gender'] == 'male') ? 'checked' : '' ?>> Мужской</label>
                    <label><input type="radio" name="gender" value="female" <?= (isset($old_data['gender']) && $old_data['gender'] == 'female') ? 'checked' : '' ?>> Женский</label>
                </div>
            </div>

            <div class="form-group">
                <label class="required">Любимый язык программирования</label>
                <select name="languages[]" multiple required size="6">
                    <?php
                    $langs = ['Pascal', 'C', 'C++', 'JavaScript', 'PHP', 'Python', 'Java', 'Haskell', 'Clojure', 'Prolog', 'Scala', 'Go'];
                    $selected = $old_data['languages'] ?? [];
                    foreach ($langs as $lang): ?>
                        <option value="<?= $lang ?>" <?= in_array($lang, $selected) ? 'selected' : '' ?>><?= $lang ?></option>
                    <?php endforeach; ?>
                </select>
                <small>Удерживайте Ctrl (Cmd) для выбора нескольких языков</small>
            </div>

            <div class="form-group">
                <label>Биография</label>
                <textarea name="biography" rows="5" placeholder="Расскажите немного о себе..."><?= htmlspecialchars($old_data['biography'] ?? '') ?></textarea>
            </div>

            <div class="checkbox-group">
                <label><input type="checkbox" name="agreed" <?= isset($old_data['agreed']) ? 'checked' : '' ?>> Я ознакомлен(а) с контрактом</label>
            </div>

            <button type="submit">💾 Сохранить</button>
        </form>
    </div>
</div>
</body>
</html>