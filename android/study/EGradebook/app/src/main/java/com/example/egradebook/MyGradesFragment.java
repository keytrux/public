package com.example.egradebook;

import static android.content.Context.MODE_PRIVATE;

import android.annotation.SuppressLint;
import android.content.Intent;
import android.content.SharedPreferences;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.graphics.Color;
import android.os.Bundle;
import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.fragment.app.Fragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.Spinner;
import android.widget.TableLayout;
import android.widget.TableRow;
import android.widget.TextView;
import android.widget.Toast;
import java.util.ArrayList;
import java.util.List;

public class MyGradesFragment extends Fragment {

    DatabaseHelper dbHelper;
    String id_student;
    TextView exam_text_student, credit_text_student;
    Spinner spinnerCourse, spinnerSemester;
    Button buttonLoadGrades, buttonPractices, buttonCoursework;
    TableLayout tableExam, tableCredit;

    @SuppressLint("MissingInflatedId")
    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {

        View view = inflater.inflate(R.layout.fragment_my_grades, container, false);

        dbHelper = new DatabaseHelper(requireActivity());

        String[] courses = {"1 курс", "2 курс", "3 курс", "4 курс"}; // массив с курсами
        String[] semesters = {"1 семестр", "2 семестр"}; // массив с семестрами

        // получаем элементы с активности
        spinnerCourse = view.findViewById(R.id.spinner_course);
        spinnerSemester = view.findViewById(R.id.spinner_semester);

        buttonLoadGrades = view.findViewById(R.id.button_load_grades);

        tableExam = view.findViewById(R.id.table_exam);
        tableCredit = view.findViewById(R.id.table_credit);

        exam_text_student = view.findViewById(R.id.exam_text_student);
        credit_text_student = view.findViewById(R.id.credit_text_student);

        buttonPractices = view.findViewById(R.id.button_practices);
        buttonCoursework = view.findViewById(R.id.button_coursework);

        ArrayAdapter<String> courseAdapter = new ArrayAdapter<>(getActivity(), android.R.layout.simple_spinner_item, courses);
        courseAdapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        spinnerCourse.setAdapter(courseAdapter); // спиннер с курсами

        ArrayAdapter<String> semesterAdapter = new ArrayAdapter<>(getActivity(), android.R.layout.simple_spinner_item, semesters);
        semesterAdapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        spinnerSemester.setAdapter(semesterAdapter); // спинер с семестрами

        // кнопка получить оценки
        buttonLoadGrades.setOnClickListener(v -> {
            String selectedCourse = spinnerCourse.getSelectedItem().toString().replaceAll("[^0-9]", "");
            String selectedSemester = spinnerSemester.getSelectedItem().toString().replaceAll("[^0-9]", "");

            displayGrades(selectedCourse, selectedSemester, tableExam, tableCredit); // метод для отображения данных
        });

        // кнопка практики
        buttonPractices.setOnClickListener(v -> {
            Intent intent = new Intent(getActivity(), Practices.class);
            startActivity(intent);
        });

        // кнопка курсовые работы
        buttonCoursework.setOnClickListener(v -> {
            Intent intent = new Intent(getActivity(), Coursework.class);
            startActivity(intent);
        });

        return view; // Вернуть созданное представление
    }

    // метод для отображения данных зачетки
    @SuppressLint("Range")
    private void displayGrades(String course, String semester, TableLayout tableExam, TableLayout tableCredit) {
        // Очистить предыдущие данные в таблицах и в заголовках
        exam_text_student.setText("");
        credit_text_student.setText("");
        tableExam.removeAllViews();
        tableCredit.removeAllViews();

        SharedPreferences sharedPreferences = requireActivity().getSharedPreferences("StudentPrefs", MODE_PRIVATE);
        Integer id_user = sharedPreferences.getInt("id_user", 0); // получем id_user с сохраненных данных

        SQLiteDatabase db = dbHelper.getWritableDatabase();

        String selectStudent = "SELECT * FROM students WHERE id_user = ?";
        Cursor cursorStudent = db.rawQuery(selectStudent, new String[]{String.valueOf(id_user)});

        if (cursorStudent.moveToFirst()) {
            id_student = cursorStudent.getString(cursorStudent.getColumnIndex("id_student"));
        }

        List<String[]> examList = new ArrayList<>();
        List<String[]> creditList = new ArrayList<>();

        String selectGrade = "SELECT g.grade, g.date, s.course, s.semester, s.name_subject, s.final_type, s.credit_hours FROM gradebooks g JOIN subjects s ON g.id_subject = s.id_subject" +
                " WHERE g.id_student = ? AND s.course = ? AND s.semester = ?";
        Cursor cursorGrade = db.rawQuery(selectGrade, new String[]{String.valueOf(id_student), course, semester});

        if (cursorGrade.moveToFirst()) {
            do {
                String date = cursorGrade.getString(cursorGrade.getColumnIndex("date")); // дата
                String name_subject = cursorGrade.getString(cursorGrade.getColumnIndex("name_subject")); // название предмета
                String final_type = cursorGrade.getString(cursorGrade.getColumnIndex("final_type")); // тип (экзамен/зачет)
                String credit_hours = cursorGrade.getString(cursorGrade.getColumnIndex("credit_hours")); // кол-во часов
                String grade = cursorGrade.getString(cursorGrade.getColumnIndex("grade")); // оценка

                if (final_type.equals("Экзамен")) {
                    // Добавление данных в список
                    String[] exam = {name_subject, date, credit_hours, grade};
                    examList.add(exam);
                }

                if (final_type.equals("Зачет")) {
                    // Добавление данных в список
                    String[] credit = {name_subject, date, credit_hours, grade};
                    creditList.add(credit);
                }
            }
            while (cursorGrade.moveToNext());
        }
        else {
            Toast.makeText(getActivity(), "Нет записей!", Toast.LENGTH_SHORT).show();
        }

        if (cursorStudent != null) {
            cursorStudent.close(); // Закрываем cursorStudent
        }
        if (cursorGrade != null) {
            cursorGrade.close(); // Закрываем cursorStudent
        }

        db.close();

        if (!examList.isEmpty()) {
            exam_text_student.setText("Экзамены");

            // Добавление заголовков для таблицы экзаменов
            TableRow headerRowExam = new TableRow(getActivity());
            String[] examHeaders = {"Предмет", "Дата", "Кол-во часов", "Оценка"};

            // Создаем 1 строку в таблицу с заголовками
            for (String header : examHeaders) {
                TextView textView = new TextView(getActivity());
                textView.setText(header);
                textView.setPadding(10, 10, 10, 10);  // отступы между столбцами
                textView.setBackgroundColor(Color.LTGRAY); // заливка
                headerRowExam.addView(textView);
            }
            addSeparatorLine(tableExam); // добавляем разделитель
            tableExam.addView(headerRowExam); // добавляем в таблицу строку с заголовками
            addSeparatorLine(tableExam); // добавляем разделитель

            // Заполнение таблицы (создаем элементы)
            for (String[] exam : examList) {
                TableRow row = new TableRow(getActivity());

                for (String data : exam) {
                    TextView textView = new TextView(getActivity());
                    textView.setText(data);
                    textView.setPadding(10, 10, 10, 10);  // отступы между столбцами
                    row.addView(textView);
                }

                tableExam.addView(row); // добавляем в таблицу строку
                addSeparatorLine(tableExam); // добавляем разделитель
            }
        }

        if (!creditList.isEmpty()) {
            credit_text_student.setText("Зачеты");

            // Добавление заголовков для таблицы зачетов
            TableRow headerRowCredit = new TableRow(getActivity());
            String[] creditHeaders = {"Предмет", "Дата", "Кол-во часов", "Оценка"};

            // Создаем 1 строку в таблицу с заголовками
            for (String header : creditHeaders) {
                TextView textView = new TextView(getActivity());
                textView.setText(header);
                textView.setPadding(10, 10, 10, 10); // отступы между столбцами
                textView.setBackgroundColor(Color.LTGRAY); // заливка
                headerRowCredit.addView(textView);
            }

            addSeparatorLine(tableCredit); // добавляем разделитель
            tableCredit.addView(headerRowCredit); // добавляем в таблицу строку с заголовками
            addSeparatorLine(tableCredit); // добавляем разделитель

            // Заполнение таблицы зачетов
            for (String[] credit : creditList) {
                TableRow row = new TableRow(getActivity());

                for (String data : credit) {
                    TextView textView = new TextView(getActivity());
                    textView.setText(data);
                    textView.setPadding(10, 10, 10, 10); // отступы между столбцами
                    row.addView(textView);
                }

                tableCredit.addView(row); // добавляем в таблицу строку
                addSeparatorLine(tableCredit); // добавляем разделитель
            }
        }
    }

    // метод для создания разделителя в таблице
    private void addSeparatorLine(TableLayout tableLayout) {
        View line = new View(getActivity());
        line.setLayoutParams(new TableRow.LayoutParams(
                TableRow.LayoutParams.MATCH_PARENT, 1)); // устанавливаем высоту 1 пиксель
        line.setBackgroundColor(Color.BLACK); // цвет линии
        tableLayout.addView(line);
    }
}
