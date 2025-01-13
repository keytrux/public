package com.example.habitflow;

import androidx.appcompat.app.AppCompatActivity;
import android.annotation.SuppressLint;
import android.content.Intent;
import android.content.SharedPreferences;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.os.Bundle;
import android.text.Editable;
import android.text.TextWatcher;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.LinearLayout;
import android.widget.Toast;
import java.text.SimpleDateFormat;
import java.util.Date;

public class LoginRegister extends AppCompatActivity {

    boolean isLoginMode = true;

    DatabaseHelper dbHelper;
    EditText etPhone, etPassword, etConfirmPassword, etName, etEmail;
    Button btnSwitchMode, btnSubmit;
    LinearLayout layoutExtraFields;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login_register);

        checkLoginStatus();
        dbHelper = new DatabaseHelper(this);

        SQLiteDatabase db = dbHelper.getWritableDatabase(); // Открываем базу данных для записи
        db.close();

        // Получаем элементы с активности
        etPhone = findViewById(R.id.etPhone);
        etPassword = findViewById(R.id.etPassword);
        etConfirmPassword = findViewById(R.id.etConfirmPassword);
        etName = findViewById(R.id.etName);
        etEmail = findViewById(R.id.etEmail);
        btnSwitchMode = findViewById(R.id.btnSwitchMode);
        btnSubmit = findViewById(R.id.btnSubmit);
        layoutExtraFields = findViewById(R.id.layoutExtraFields);

        etPhone.addTextChangedListener(new TextWatcher() // маска для номера телефона
        {
            private boolean isUpdating;

            @Override
            public void beforeTextChanged(CharSequence s, int start, int count, int after) {
            }

            @Override
            public void onTextChanged(CharSequence s, int start, int before, int count) {
                if (isUpdating) {
                    return;
                }
                isUpdating = true;

                // Удаляем старые символы, чтобы оставить только цифры
                String cleaned = s.toString().replaceAll("[^\\d]", "");

                // Собираем маску номера телефона (+7 (XXX) XXX-XX-XX)
                String formatted = "";
                if (cleaned.length() > 0) {
                    formatted = "+7";
                }
                if (cleaned.length() > 1) {
                    formatted += " (" + cleaned.substring(1, Math.min(4, cleaned.length()));
                }
                if (cleaned.length() > 4) {
                    formatted += ") " + cleaned.substring(4, Math.min(7, cleaned.length()));
                }
                if (cleaned.length() > 7) {
                    formatted += "-" + cleaned.substring(7, Math.min(9, cleaned.length()));
                }
                if (cleaned.length() > 9) {
                    formatted += "-" + cleaned.substring(9, Math.min(11, cleaned.length()));
                }

                // Устанавливаем результат в EditText
                etPhone.setText(formatted);
                etPhone.setSelection(formatted.length()); // Устанавливаем курсор в конец
                isUpdating = false;
            }

            @Override
            public void afterTextChanged(Editable s) {
            }
        });

        // Обработка кнопки переключения режима
        btnSwitchMode.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                toggleMode();
            }
        });

        btnSubmit.setOnClickListener(new View.OnClickListener() // листенер на кнопку входа
        {
            @Override
            public void onClick(View v) {
                if (isLoginMode) {
                    // Логика авторизации
                    loginUser();
                    Integer id_user = loginUser();
                    if (id_user != 0)
                    {
                        // открытие активности пользователя
                        Intent intent = new Intent(LoginRegister.this, User.class);
                        // Устанавливаем флаги
                        intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_NEW_TASK);
                        startActivity(intent);

                        // Завершаем текущую активность авторизации
                        finish();
                    }
                }
                else {
                    // Логика регистрации
                    registerUser();
                }
            }
        });

        // Устанавливаем начальное состояние
        updateUI();
    }

    // метод для переключения режимов (авторизация/регистрация)
    private void toggleMode() {
        isLoginMode = !isLoginMode; // Переключаем режим
        updateUI();
    }

    // метод для обновления элементов в зависимости от режимов
    private void updateUI() {
        if (isLoginMode) // Режим авторизации: скрываем лишние поля
        {
            layoutExtraFields.setVisibility(View.GONE);
            btnSubmit.setText("Войти");
            btnSwitchMode.setText("Перейти к регистрации");
        }
        else // Режим регистрации: показываем дополнительные поля
        {
            layoutExtraFields.setVisibility(View.VISIBLE);
            btnSubmit.setText("Зарегистрироваться");
            btnSwitchMode.setText("Перейти к авторизации");
        }
    }

    // метод авторизации
    @SuppressLint("Range")
    private Integer loginUser()
    {
        String phone = etPhone.getText().toString();
        String password = etPassword.getText().toString();
        int id_user = 0;

        if (phone.isEmpty() || password.isEmpty()) {
            Toast.makeText(this, "Вы ввели не все данные!", Toast.LENGTH_SHORT).show();
            return 0;
        }

        SQLiteDatabase db = dbHelper.getWritableDatabase();
        boolean userFound = false;
        String selectQuery = "SELECT * FROM users WHERE phone = ? AND password = ?"; // запрос на поиск пользователя
        Cursor cursor = db.rawQuery(selectQuery, new String[]{phone, password});
        if (cursor.moveToFirst()) {  // Проверяем, есть ли результаты
            userFound = true; // Пользователь найден
            id_user = cursor.getInt(cursor.getColumnIndex("id_user"));
            Toast.makeText(this, "Вы успешно авторизованы!", Toast.LENGTH_SHORT).show();
        }
        else
        {
            Toast.makeText(this, "Пользователь не найден!", Toast.LENGTH_SHORT).show();
            userFound = false;
        }

        if (userFound) {
            saveUserPreferences(phone, id_user);
        }

        // Закрываем курсор и базу данных
        cursor.close();
        db.close();

        return id_user; // Возвращаем результат
    }

    // метод для сохранения данных пользователя
    private void saveUserPreferences(String phone, Integer id_user) {
        SharedPreferences sharedPreferences = getSharedPreferences("UserPrefs", MODE_PRIVATE);
        SharedPreferences.Editor editor = sharedPreferences.edit();
        editor.putString("phone", phone);
        editor.putInt("id_user", id_user);
        editor.apply(); // Сохраняем данные
    }

    // метод регистрации
    @SuppressLint("Range")
    private void registerUser()
    {
        String phone = etPhone.getText().toString();
        String password = etPassword.getText().toString();
        String confirmPassword = etConfirmPassword.getText().toString();
        String name = etName.getText().toString();
        String email = etEmail.getText().toString();
        String currentDate = new SimpleDateFormat("dd.MM.yyyy").format(new Date());
        int userId = -1;

        if (phone.trim().isEmpty())
        {
            Toast.makeText(this, "Вы не ввели номер телефона!", Toast.LENGTH_SHORT).show();
            return;
        }

        if (password.trim().isEmpty())
        {
            Toast.makeText(this, "Вы не ввели пароль!", Toast.LENGTH_SHORT).show();
            return;
        }

        if (confirmPassword.trim().isEmpty())
        {
            Toast.makeText(this, "Вы не ввели повторно пароль!", Toast.LENGTH_SHORT).show();
            return;
        }

        if (name.trim().isEmpty())
        {
            Toast.makeText(this, "Вы не ввели ФИО!", Toast.LENGTH_SHORT).show();
            return;
        }

        if (email.trim().isEmpty())
        {
            Toast.makeText(this, "Вы не ввели email!", Toast.LENGTH_SHORT).show();
            return;
        }

        if(!password.equals(confirmPassword))
        {
            Toast.makeText(this, "Пароли не совпадают!", Toast.LENGTH_SHORT).show();
            return;
        }

        SQLiteDatabase db = dbHelper.getWritableDatabase(); // Открываем базу данных

        // проверка используется ли телефон
        Cursor cursorUser = db.rawQuery("SELECT * FROM users WHERE phone = ?", new String[]{phone});
        if (cursorUser.moveToFirst()) {
            Toast.makeText(this, "Этот номер телефона уже используется", Toast.LENGTH_SHORT).show();
            return;
        }

        // добавление пользователя
        String insertQuery = "INSERT INTO users (phone, password, email, name, created) VALUES (?, ?, ?, ?, ?)";
        db.execSQL(insertQuery, new Object[]{phone, password, email, name, currentDate});

        Toast.makeText(this, "Вы успешно зарегистрировались!", Toast.LENGTH_SHORT).show();

        db.close(); // Закрываем базу данных
    }

    private void checkLoginStatus() {
        SharedPreferences sharedPreferences = getSharedPreferences("UserPrefs", MODE_PRIVATE); // проверяем сохранены ли данные студента
        String phone = sharedPreferences.getString("phone", null);

        if (phone != null) {
            // Если номер телефона сохранен, автоматически перейти в нужную активность
            Intent intent = new Intent(this, User.class);
            startActivity(intent);
            finish(); // Закрываем текущий экран
        }
    }
}