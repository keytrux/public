package com.example.egradebook;

import android.annotation.SuppressLint;
import android.content.Intent;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.os.Bundle;

import androidx.appcompat.app.AppCompatActivity;

import android.text.Editable;
import android.text.TextWatcher;
import android.util.Log;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.LinearLayout;
import android.widget.Spinner;
import android.widget.Toast;

import java.util.ArrayList;

public class LoginStudent extends AppCompatActivity {

    private boolean isLoginMode = true; // Режим по умолчанию - Авторизация

    private DatabaseHelper dbHelper;

    private EditText etPhone, etPassword, etConfirmPassword, etName, etEmail;
    private Button btnSwitchMode, btnSubmit;
    private LinearLayout layoutExtraFields;
    private Spinner groupSpinner;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_student_login);

        dbHelper = new DatabaseHelper(this);

        groupSpinner = findViewById(R.id.mySpinner);

        // Получаем данные из таблицы group_list
        ArrayList<String> data = getSpinnerData();
        data.add(0, "Выберите группу"); // Добавляем заголовок в список

        // Проверяем, что данные не пустые
        if (!data.isEmpty()) {
            // Создаем адаптер для выпадающего списка
            ArrayAdapter<String> adapter = new ArrayAdapter<>(
            this,
                android.R.layout.simple_spinner_dropdown_item,
                data
            );
            groupSpinner.setAdapter(adapter);

            groupSpinner.setSelection(0, false);
            groupSpinner.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
                @Override
                public void onItemSelected(AdapterView<?> parent, View view, int position, long id) {
                    if (position == 0) {
                        return; // Если выбран "Выберите группу", ничего не делаем
                    }
                    // Ваш код обработки выбора группы
                }

                @Override
                public void onNothingSelected(AdapterView<?> parent) {
                    // Если ничего не выбрано
                }
            });
        }

        // Инициализируем UI элементы
        etPhone = findViewById(R.id.etPhone);
        etPassword = findViewById(R.id.etPassword);
        etConfirmPassword = findViewById(R.id.etConfirmPassword);
        etName = findViewById(R.id.etName);
        etEmail = findViewById(R.id.etEmail);
        btnSwitchMode = findViewById(R.id.btnSwitchMode);
        btnSubmit = findViewById(R.id.btnSubmit);
        layoutExtraFields = findViewById(R.id.layoutExtraFields);

        etPhone.addTextChangedListener(new TextWatcher() {
            private boolean isUpdating;

            @Override
            public void beforeTextChanged(CharSequence s, int start, int count, int after) {
                // Метод перед изменением текста
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
                // Метод после изменения текста
            }
        });

        // Обработка кнопки переключения режима
        btnSwitchMode.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                toggleMode();
            }
        });

        btnSubmit.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if (isLoginMode) {
                    // Логика авторизации
                    loginUser();
                    if (loginUser() == true)
                    {
                        Intent intent = new Intent(LoginStudent.this, StudentPersonalAccount.class);
                        startActivity(intent);
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

    // Метод для выполнения SELECT-запроса к group_list
    private ArrayList<String> getSpinnerData() {
        ArrayList<String> spinnerData = new ArrayList<>();
        String selectQuery = "SELECT name_group FROM group_list";

        Cursor cursor = null;
        try {
            SQLiteDatabase db = dbHelper.getWritableDatabase();
            cursor = db.rawQuery(selectQuery, null);

            // Берем значения из первого столбца (name_group)
            if (cursor.moveToFirst()) {
                do {
                    spinnerData.add(cursor.getString(0)); // 0 - это index столбца name_group
                } while (cursor.moveToNext());
            }
        } catch (Exception e) {
            e.printStackTrace(); // Логируем ошибку, если что-то пошло не так
        } finally {
            if (cursor != null) cursor.close(); // Закрываем курсор!
        }

        return spinnerData;
    }


    private void toggleMode() {
        isLoginMode = !isLoginMode; // Переключаем режим
        updateUI();
    }

    private void updateUI() {
        if (isLoginMode) {
            // Режим авторизации: скрываем лишние поля
            layoutExtraFields.setVisibility(View.GONE);
            btnSubmit.setText("Войти");
            btnSwitchMode.setText("Перейти к регистрации");
        } else {
            // Режим регистрации: показываем дополнительные поля
            layoutExtraFields.setVisibility(View.VISIBLE);
            btnSubmit.setText("Зарегистрироваться");
            btnSwitchMode.setText("Перейти к авторизации");
        }
    }
    private boolean loginUser()
    {
        String phone = etPhone.getText().toString();
        String password = etPassword.getText().toString();

        if (phone.isEmpty() || password.isEmpty()) {
            Log.d("LOGIN_ERROR", "Телефон или пароль пустые!");
            Toast.makeText(this, "Вы ввели не все данные!", Toast.LENGTH_SHORT).show();
            return false;
        }

        SQLiteDatabase db = dbHelper.getWritableDatabase();
        boolean userFound = false;
        String selectQuery = "SELECT * FROM users WHERE phone = ? AND password = ?";
        Cursor cursor = db.rawQuery(selectQuery, new String[]{phone, password});
        if (cursor.moveToFirst()) {  // Проверяет, есть ли результаты
            userFound = true; // Пользователь найден
            Toast.makeText(this, "Вы успешно авторизованы!", Toast.LENGTH_SHORT).show();
        }
        else
        {
            Toast.makeText(this, "Пользователь не найден!", Toast.LENGTH_SHORT).show();
            userFound = false;
        }

        // Закрываем курсор и базу данных
        cursor.close();
        db.close();

        Log.d("LOGIN_STATUS", userFound ? "Пользователь найден!" : "Пользователь не найден!");

        return userFound; // Возвращаем результат
    }
    @SuppressLint("Range")
    private void registerUser()
    {
        String phone = etPhone.getText().toString();
        String password = etPassword.getText().toString();
        String confirmPassword = etConfirmPassword.getText().toString();
        String name = etName.getText().toString();
        String email = etEmail.getText().toString();
        String selectedGroup = groupSpinner.getSelectedItem().toString();
        int groupId = -1;
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

        if (groupSpinner.getSelectedItemPosition() == 0)
        {
            Toast.makeText(this, "Вы не выбрали группу!", Toast.LENGTH_SHORT).show();
            return;
        }


        SQLiteDatabase db = dbHelper.getWritableDatabase(); // Открываем базу данных

        // добавление пользователя
        String insertQuery = "INSERT INTO users (phone, password, email, role) VALUES (?, ?, ?, ?)";
        db.execSQL(insertQuery, new Object[]{phone, password, email, "студент"});

        // поиск названия id группы по выбранному селектору
        Cursor cursor = db.rawQuery("SELECT id_group FROM group_list WHERE name_group = ?", new String[]{selectedGroup});
        if (cursor.moveToFirst()) {
            groupId = cursor.getInt(0); // Получаем ID группы
        }
        cursor.close();

        // Получаем id вставленного пользователя
        Cursor userCursor = db.rawQuery("SELECT last_insert_rowid() AS id_user", null);
        if (userCursor.moveToFirst()) {
            userId = userCursor.getInt(userCursor.getColumnIndex("id_user"));
        }
        userCursor.close();

        // создание студента
        if (userId != -1 && groupId != -1) {
            String insertStudentQuery = "INSERT INTO students (full_name, id_group, id_user) VALUES (?, ?, ?)";
            db.execSQL(insertStudentQuery, new Object[]{name, groupId, userId});
            Toast.makeText(this, "Вы успешно зарегистрированы!", Toast.LENGTH_SHORT).show();
        }
        else {
            Toast.makeText(this, "Ошибка! Группа или пользователь не найдены", Toast.LENGTH_SHORT).show();
        }

        db.close(); // Закрываем базу данных
    }

}
