<form action="" method="POST">
    <div class="form-group">
        <label>ФИО:</label>
        <input type="text" name="full_name" required maxlength="150">
    </div>

    <div class="form-group">
        <label>Телефон:</label>
        <input type="tel" name="phone" required>
    </div>

    <div class="form-group">
        <label>E-mail:</label>
        <input type="email" name="email" required>
    </div>

    <div class="form-group">
        <label>Дата рождения:</label>
        <input type="date" name="birth_date" required>
    </div>

    <div class="form-group">
        <label>Пол:</label>
        <label><input type="radio" name="gender" value="male" required> Мужской</label>
        <label><input type="radio" name="gender" value="female"> Женский</label>
    </div>

    <div class="form-group">
        <label>Любимый язык программирования:</label>
        <select name="languages[]" multiple required size="6">
            <option value="Pascal">Pascal</option>
            <option value="C">C</option>
            <option value="C++">C++</option>
            <option value="JavaScript">JavaScript</option>
            <option value="PHP">PHP</option>
            <option value="Python">Python</option>
            <option value="Java">Java</option>
            <option value="Haskell">Haskell</option>
            <option value="Clojure">Clojure</option>
            <option value="Prolog">Prolog</option>
            <option value="Scala">Scala</option>
            <option value="Go">Go</option>
        </select>
        <small>Удерживайте Ctrl для выбора нескольких языков</small>
    </div>

    <div class="form-group">
        <label>Биография:</label>
        <textarea name="biography" rows="5"></textarea>
    </div>

    <div class="form-group">
        <label>
            <input type="checkbox" name="agreed" required> Я ознакомлен(а) с контрактом
        </label>
    </div>

    <button type="submit">Сохранить</button>
</form>