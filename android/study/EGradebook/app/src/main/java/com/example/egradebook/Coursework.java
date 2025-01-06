package com.example.egradebook;

import android.annotation.SuppressLint;
import android.content.SharedPreferences;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.graphics.Color;
import android.os.Bundle;
import android.view.View;
import android.widget.TableLayout;
import android.widget.TableRow;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

import java.util.ArrayList;
import java.util.List;

public class Coursework extends AppCompatActivity {

    SQLiteDatabase db;
    DatabaseHelper dbHelper;
    String id_student, selectStudent, selectGrade;
    Integer id_user;
    TableLayout tableCoursework;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_coursework);

        tableCoursework = this.findViewById(R.id.table_coursework);

        displayGrades(tableCoursework); // вызываем метод для вывод данных о курсовых работ в таблицу
    }

    // метод для вывода данных
    @SuppressLint("Range")
    private void displayGrades(TableLayout tableCoursework) {
        tableCoursework.removeAllViews();

        List<String[]> courseworkList = new ArrayList<>();

        dbHelper = new DatabaseHelper(this); // инициализация бд
        db = dbHelper.getWritableDatabase();

        SharedPreferences sharedPreferences = this.getSharedPreferences("StudentPrefs", MODE_PRIVATE);
        id_user = sharedPreferences.getInt("id_user", 0); // берем id пользователя из сохранённых данных

        selectStudent = "SELECT * FROM students WHERE id_user = ?"; // запрос на получение студента по id_user
        Cursor cursorStudent = db.rawQuery(selectStudent, new String[]{String.valueOf(id_user)});

        if (cursorStudent.moveToFirst()) {
            id_student = cursorStudent.getString(cursorStudent.getColumnIndex("id_student")); // сохраняем найденный id_student
        }

        selectGrade = "SELECT g.grade, g.date, w.course, w.semester, w.name_work, w.type FROM gradebooks g JOIN works w ON g.id_work = w.id_work" +
                " WHERE g.id_student = ? AND w.type = ?" +
                " ORDER BY w.course, w.semester"; // запрос на получение курсовых и данных из зачетки
        Cursor cursorGrade = db.rawQuery(selectGrade, new String[]{String.valueOf(id_student), "Курсовая работа"});

        if (cursorGrade.moveToFirst()) {
            do {
                // Извлекаем данные по каждому столбцу
                String date = cursorGrade.getString(cursorGrade.getColumnIndex("date")); // дата
                String name_work = cursorGrade.getString(cursorGrade.getColumnIndex("name_work")); // название работы
                String course = cursorGrade.getString(cursorGrade.getColumnIndex("course")); // курс
                String semester = cursorGrade.getString(cursorGrade.getColumnIndex("semester")); // семестр
                String grade = cursorGrade.getString(cursorGrade.getColumnIndex("grade")); // оценка

                String[] partices = {name_work, date, course, semester, grade}; // сохраняем в массив данные
                courseworkList.add(partices);
            }
            while (cursorGrade.moveToNext());
        }
        else {
            Toast.makeText(this, "Нет записей!", Toast.LENGTH_SHORT).show();
        }

        if (cursorStudent != null) {
            cursorStudent.close(); // Закрываем cursorStudent
        }
        if (cursorGrade != null) {
            cursorGrade.close(); // Закрываем cursorGrade
        }

        db.close();

        // Добавление заголовков для таблицы курсовые работы
        TableRow headerRowExam = new TableRow(this);
        String[] examHeaders = {"Название", "Дата", "Курс", "Семестр", "Оценка"};

        // Создаем 1 строку в таблицу с заголовками
        for (String header : examHeaders) {
            TextView textView = new TextView(this);
            textView.setText(header);
            textView.setPadding(10, 10, 10, 10); // отступы между столбцами
            textView.setBackgroundColor(Color.LTGRAY); // заливка
            headerRowExam.addView(textView);
        }

        addSeparatorLine(tableCoursework); // добавляем разделитель
        tableCoursework.addView(headerRowExam); // добавляем в таблицу строку с заголовками
        addSeparatorLine(tableCoursework); // добавляем разделитель

        // Заполнение таблицы курсовых работ
        for (String[] coursework : courseworkList) // проходимся по массиву с данными по курсовым
        {
            TableRow row = new TableRow(this);

            for (String data : coursework) {
                TextView textView = new TextView(this);
                textView.setText(data);
                textView.setPadding(10, 10, 10, 10); // отступы между столбцами
                row.addView(textView);
            }

            tableCoursework.addView(row); // добавляем в таблицу строку
            addSeparatorLine(tableCoursework); // добавляем разделитель
        }
        addSeparatorLine(tableCoursework); // добавляем разделитель
    }

    private void addSeparatorLine(TableLayout tableLayout) // метод для создания разделителя
    {
        View line = new View(this);
        line.setLayoutParams(new TableRow.LayoutParams(
                TableRow.LayoutParams.MATCH_PARENT, 1)); // высота 1 пиксель
        line.setBackgroundColor(Color.BLACK); // цвет линии
        tableLayout.addView(line);
    }
}
