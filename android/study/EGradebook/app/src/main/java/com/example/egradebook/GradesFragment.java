package com.example.egradebook;

import static android.content.Context.MODE_PRIVATE;

import android.annotation.SuppressLint;
import android.app.DatePickerDialog;
import android.content.SharedPreferences;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.graphics.Color;
import android.os.Build;
import android.os.Bundle;
import android.text.InputType;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Spinner;
import android.widget.TableLayout;
import android.widget.TableRow;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.annotation.RequiresApi;
import androidx.fragment.app.Fragment;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.Calendar;
import java.util.List;

public class GradesFragment extends Fragment {

    View view;
    DatabaseHelper dbHelper;
    SQLiteDatabase db;
    Spinner groupSpinner, studentSpinner, courseSpinner, semesterSpinner;
    Button button_submit;
    String id_student, id_teacher;
    TextView exam_text_teacher, credit_text_teacher, practices_text_teacher, coursework_text_teacher;
    TableLayout tableExam, tableCredit, tablePractices, tableCoursework;

    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {

        view = inflater.inflate(R.layout.fragment_grades, container, false);

        dbHelper = new DatabaseHelper(getActivity());

        db = dbHelper.getWritableDatabase();

        // Получение элементов с активности
        groupSpinner = view.findViewById(R.id.spinner_group);
        studentSpinner = view.findViewById(R.id.spinner_student);
        courseSpinner = view.findViewById(R.id.spinner_course);
        semesterSpinner = view.findViewById(R.id.spinner_semester);

        button_submit = view.findViewById(R.id.button_submit);

        exam_text_teacher = view.findViewById(R.id.exam_text_teacher);
        credit_text_teacher = view.findViewById(R.id.credit_text_teacher);
        practices_text_teacher = view.findViewById(R.id.practices_text_teacher);
        coursework_text_teacher = view.findViewById(R.id.coursework_text_teacher);

        tableExam = view.findViewById(R.id.table_exam_teacher);
        tableCredit = view.findViewById(R.id.table_credit_teacher);
        tablePractices = view.findViewById(R.id.table_practices_teacher);
        tableCoursework = view.findViewById(R.id.table_coursework_teacher);

        // Получаем данные из таблицы group_list
        ArrayList<String> data_group = getSpinnerData();
        data_group.add(0, "Выберите группу"); // Добавляем заголовок в список

        // Проверяем, что данные не пустые
        if (!data_group.isEmpty()) {
            // Создаем адаптер для выпадающего списка
            ArrayAdapter<String> adapter = new ArrayAdapter<>(
                    getActivity(),
                    android.R.layout.simple_spinner_dropdown_item,
                    data_group
            );
            groupSpinner.setAdapter(adapter);

            groupSpinner.setSelection(0, false);
            groupSpinner.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
                @Override
                public void onItemSelected(AdapterView<?> parent, View view, int position, long id) {
                    if (position == 0) {
                        studentSpinner.setAdapter(null);
                        studentSpinner.setVisibility(View.GONE);
                        courseSpinner.setVisibility(View.GONE);
                        semesterSpinner.setVisibility(View.GONE);
                        return; // Если выбран "Выберите группу", ничего не делаем
                    }

                    studentSpinner.setVisibility(View.VISIBLE);
                    courseSpinner.setVisibility(View.VISIBLE);
                    semesterSpinner.setVisibility(View.VISIBLE);

                    ArrayList<String> students = getStudentsForGroup(data_group.get(position));
                    students.add(0, "Выберите студента"); // Добавляем заголовок в список

                    ArrayAdapter<String> studentAdapter = new ArrayAdapter<>(
                            getActivity(),
                            android.R.layout.simple_spinner_dropdown_item,
                            students
                    );
                    studentSpinner.setAdapter(studentAdapter);

                    // Добавление данных в спиннер курсов
                    ArrayAdapter<String> courseAdapter = new ArrayAdapter<>(getActivity(), android.R.layout.simple_spinner_item,
                            Arrays.asList("Выберите курс", "1", "2", "3", "4"));
                    courseAdapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
                    courseSpinner.setAdapter(courseAdapter);

                    // Добавление данных в спиннер семестров
                    ArrayAdapter<String> semesterAdapter = new ArrayAdapter<>(getActivity(), android.R.layout.simple_spinner_item,
                            Arrays.asList("Выберите семестр", "1", "2"));
                    semesterAdapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
                    semesterSpinner.setAdapter(semesterAdapter);

                }

                @Override
                public void onNothingSelected(AdapterView<?> parent) {
                    // Если ничего не выбрано
                }
            });
        }

        button_submit.setOnClickListener(new View.OnClickListener() { // нажатие кнопки Получить зачетку
            @SuppressLint("Range")
            @RequiresApi(api = Build.VERSION_CODES.N)
            @Override
            public void onClick(View view) {

                if (groupSpinner.getSelectedItemPosition() == 0) {
                    Toast.makeText(getActivity(), "Вы не выбрали группу!", Toast.LENGTH_SHORT).show();
                    return;
                }
                if (studentSpinner.getSelectedItemPosition() == 0) {
                    Toast.makeText(getActivity(), "Вы не выбрали студента!", Toast.LENGTH_SHORT).show();
                    return;
                }
                if (courseSpinner.getSelectedItemPosition() == 0) {
                    Toast.makeText(getActivity(), "Вы не выбрали курс!", Toast.LENGTH_SHORT).show();
                    return;
                }
                if (semesterSpinner.getSelectedItemPosition() == 0) {
                    Toast.makeText(getActivity(), "Вы не выбрали семестр!", Toast.LENGTH_SHORT).show();
                    return;
                }

                // Очищаем спиннеры
                exam_text_teacher.setText("");
                credit_text_teacher.setText("");
                practices_text_teacher.setText("");
                coursework_text_teacher.setText("");

                // Очищаем таблицы
                tableExam.removeAllViews();
                tableCredit.removeAllViews();
                tablePractices.removeAllViews();
                tableCoursework.removeAllViews();

                // Получаем выбранные значения в спиннерах
                String selectedStudent = studentSpinner.getSelectedItem().toString();
                int selectedCourse = Integer.parseInt(courseSpinner.getSelectedItem().toString());
                int selectedSemester = Integer.parseInt(semesterSpinner.getSelectedItem().toString());

                SharedPreferences sharedPreferences = getActivity().getSharedPreferences("TeacherPrefs", MODE_PRIVATE); // берем id_user с сохраненных данных
                Integer id_user = sharedPreferences.getInt("id_user", 0);

                String selectTeacher = "SELECT * FROM teachers WHERE id_user = ?"; // запрос на получение преподавателя
                Cursor cursorTeacher = db.rawQuery(selectTeacher, new String[]{String.valueOf(id_user)});

                if (cursorTeacher.moveToFirst()) {
                    id_teacher = cursorTeacher.getString(cursorTeacher.getColumnIndex("id_teacher")); // сохраняем id_teacher
                }

                // поиск id студента
                String selectStudent = "SELECT * FROM students WHERE full_name = ?"; // запрос на получение студента
                Cursor cursorStudent = db.rawQuery(selectStudent, new String[]{(selectedStudent)});

                if (cursorStudent.moveToFirst()) {
                    id_student = cursorStudent.getString(cursorStudent.getColumnIndex("id_student")); // сохраняем id_student
                }

                // поиск предметов (экзамены и зачеты)
                String selectSubject = "SELECT g.grade, g.date, s.name_subject, s.final_type, s.credit_hours " +
                        "FROM gradebooks g " +
                        "LEFT JOIN subjects s ON g.id_subject = s.id_subject " +
                        "WHERE g.id_student = ? AND s.course = ? AND s.semester = ? AND s.id_teacher = ?";

                Cursor cursorSubject = db.rawQuery(selectSubject, new String[]{
                        String.valueOf(id_student),
                        String.valueOf(selectedCourse),
                        String.valueOf(selectedSemester),
                        String.valueOf(id_teacher)
                });

                List<String[]> examList = new ArrayList<>(); // создаем список для хранения экзаменов
                List<String[]> creditList = new ArrayList<>(); // создаем список для хранения зачетов
                List<String[]> practicesList = new ArrayList<>(); // создаем список для хранения практик
                List<String[]> courseworkList = new ArrayList<>(); // создаем список для хранения курсовых

                if (cursorSubject.moveToFirst()) {
                    do {
                        String date = cursorSubject.getString(cursorSubject.getColumnIndex("date")); // дата
                        String name_subject = cursorSubject.getString(cursorSubject.getColumnIndex("name_subject")); // название предмета
                        String final_type = cursorSubject.getString(cursorSubject.getColumnIndex("final_type")); // тип (экзамен/зачет)
                        String credit_hours = cursorSubject.getString(cursorSubject.getColumnIndex("credit_hours")); // кол-во часов
                        String grade = cursorSubject.getString(cursorSubject.getColumnIndex("grade")); // оценка

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
                    while (cursorSubject.moveToNext());
                }

                cursorStudent.close(); // Закрываем cursorStudent
                cursorSubject.close(); // Закрываем cursorSubject

                if (!examList.isEmpty()) {
                    exam_text_teacher.setText("Экзамены");

                    // Добавление заголовков для таблицы экзаменов
                    TableRow headerRowExam = new TableRow(getActivity());
                    String[] examHeaders = {"Предмет", "Дата", "Кол-во часов", "Оценка", ""};

                    // Создаем 1 строку в таблицу с заголовками
                    for (String header : examHeaders) {
                        TextView textView = new TextView(getActivity());
                        textView.setText(header);
                        textView.setPadding(10, 10, 10, 10); // отступы между столбцами
                        textView.setBackgroundColor(Color.LTGRAY); // заливка
                        headerRowExam.addView(textView);
                    }

                    addSeparatorLine(tableExam); // добавляем разделитель
                    tableExam.addView(headerRowExam); // добавляем в таблицу строку с заголовками
                    addSeparatorLine(tableExam); // добавляем разделитель

                    // Заполнение таблицы (создаем элементы)
                    for (String[] exam : examList) {
                        TableRow row = new TableRow(getActivity());

                        // Название предмета (не редактируемое)
                        TextView subjectTextView = new TextView(getActivity());
                        subjectTextView.setText(exam[0]);
                        subjectTextView.setPadding(10, 10, 10, 10);
                        row.addView(subjectTextView);

                        // Дата (выбор через DatePickerDialog)
                        EditText dateEditText = new EditText(getActivity());
                        dateEditText.setText(exam[1]);
                        dateEditText.setPadding(10, 10, 10, 10);
                        dateEditText.setFocusable(false); // Отключаем ввод с клавиатуры
                        dateEditText.setOnClickListener(v -> showDatePickerDialog(dateEditText));
                        row.addView(dateEditText);

                        // Количество часов (не редактируемое)
                        TextView hoursTextView = new TextView(getActivity());
                        hoursTextView.setText(exam[2]);
                        hoursTextView.setPadding(10, 10, 10, 10);
                        row.addView(hoursTextView);

                        // Оценка (редактируемое поле)
                        EditText gradeEditText = new EditText(getActivity());
                        gradeEditText.setText(exam[3]);
                        gradeEditText.setPadding(10, 10, 10, 10);
                        gradeEditText.setInputType(InputType.TYPE_CLASS_NUMBER);
                        row.addView(gradeEditText);

                        // Кнопка "Сохранить"
                        Button saveButton = new Button(getActivity());
                        saveButton.setText("Сохранить");
                        saveButton.setOnClickListener(v -> {
                            String updatedDate = dateEditText.getText().toString();
                            String updatedGrade = gradeEditText.getText().toString();

                            updateGradeAndDateInDb(exam[0], updatedDate, updatedGrade); // метод для проставления оценки и даты
                        });
                        row.addView(saveButton);

                        tableExam.addView(row); // добавляем в таблицу строку
                        addSeparatorLine(tableExam); // добавляем разделитель
                    }
                }

                if (!creditList.isEmpty()) {
                    credit_text_teacher.setText("Зачеты");

                    // Добавление заголовков для таблицы зачетов
                    TableRow headerRowCredit = new TableRow(getActivity());
                    String[] creditHeaders = {"Предмет", "Дата", "Кол-во часов", "Оценка", ""};

                    // Создаем 1 строку в таблицу с заголовками
                    for (String header : creditHeaders) {
                        TextView textView = new TextView(getActivity());
                        textView.setText(header);
                        textView.setPadding(10, 10, 10, 10); // отступы между столбцами
                        textView.setBackgroundColor(Color.LTGRAY); // заливка
                        headerRowCredit.addView(textView);
                    }

                    addSeparatorLine(tableCredit); // добавляем разделитель
                    tableCredit.addView(headerRowCredit); // добавляем в таблицу строку
                    addSeparatorLine(tableCredit); // добавляем разделитель

                    // Заполнение таблицы зачетов
                    for (String[] credit : creditList) {
                        TableRow row = new TableRow(getActivity());

                        // Название предмета (не редактируемое)
                        TextView subjectTextView = new TextView(getActivity());
                        subjectTextView.setText(credit[0]);
                        subjectTextView.setPadding(10, 10, 10, 10);
                        row.addView(subjectTextView);

                        // Дата (выбор через DatePickerDialog)
                        EditText dateEditText = new EditText(getActivity());
                        dateEditText.setText(credit[1]);
                        dateEditText.setPadding(10, 10, 10, 10);
                        dateEditText.setFocusable(false); // Отключаем ввод с клавиатуры
                        dateEditText.setOnClickListener(v -> showDatePickerDialog(dateEditText));
                        row.addView(dateEditText);

                        // Количество часов (не редактируемое)
                        TextView hoursTextView = new TextView(getActivity());
                        hoursTextView.setText(credit[2]);
                        hoursTextView.setPadding(10, 10, 10, 10);
                        row.addView(hoursTextView);

                        // Оценка (редактируемое поле)
                        EditText gradeEditText = new EditText(getActivity());
                        gradeEditText.setText(credit[3]);
                        gradeEditText.setPadding(10, 10, 10, 10);
                        gradeEditText.setInputType(InputType.TYPE_CLASS_NUMBER);
                        row.addView(gradeEditText);

                        // Кнопка "Сохранить"
                        Button saveButton = new Button(getActivity());
                        saveButton.setText("Сохранить");
                        saveButton.setOnClickListener(v -> {
                            String updatedDate = dateEditText.getText().toString();
                            String updatedGrade = gradeEditText.getText().toString();

                            updateGradeAndDateInDb(credit[0], updatedDate, updatedGrade); // метод для проставления оценки и даты
                        });
                        row.addView(saveButton);

                        tableCredit.addView(row); // добавляем в таблицу строку
                        addSeparatorLine(tableCredit); // добавляем разделитель
                    }
                }

                // получение работ (практики и курсовые)
                String selectWork = "SELECT g.grade, g.date, w.name_work, w.credit_hours, w.type " +
                        "FROM gradebooks g " +
                        "LEFT JOIN works w ON g.id_work = w.id_work " +
                        "WHERE g.id_student = ? AND w.course = ? AND w.semester = ?";

                Cursor cursorWork = db.rawQuery(selectWork, new String[]{
                        String.valueOf(id_student),
                        String.valueOf(selectedCourse),
                        String.valueOf(selectedSemester),
                });

                if (cursorWork.moveToFirst()) {
                    do {
                        String date = cursorWork.getString(cursorWork.getColumnIndex("date")); // дата
                        String name_work = cursorWork.getString(cursorWork.getColumnIndex("name_work")); // название работы
                        String type = cursorWork.getString(cursorWork.getColumnIndex("type")); // тип (практика/курсовая работа)
                        String credit_hours = cursorWork.getString(cursorWork.getColumnIndex("credit_hours")); // кол-во часов
                        String grade = cursorWork.getString(cursorWork.getColumnIndex("grade")); // оценка

                        if (type.equals("Практика")) {
                            // Добавление данных в список
                            String[] practices = {name_work, date, credit_hours, grade};
                            practicesList.add(practices);
                        }

                        if (type.equals("Курсовая работа")) {
                            // Добавление данных в список
                            String[] coursework = {name_work, date, credit_hours, grade};
                            courseworkList.add(coursework);
                        }
                    }
                    while (cursorWork.moveToNext());
                }

                cursorWork.close(); // Закрываем cursorWork

                if (!practicesList.isEmpty()) {
                    practices_text_teacher.setText("Практики");

                    // Добавление заголовков для таблицы практик
                    TableRow headerRowPractices = new TableRow(getActivity());
                    String[] practiesHeaders = {"Название", "Дата", "Кол-во часов", "Оценка", ""};

                    // Создаем 1 строку в таблицу с заголовками
                    for (String header : practiesHeaders) {
                        TextView textView = new TextView(getActivity());
                        textView.setText(header);
                        textView.setPadding(10, 10, 10, 10); // отступы между столбцами
                        textView.setBackgroundColor(Color.LTGRAY);
                        headerRowPractices.addView(textView);
                    }

                    addSeparatorLine(tablePractices); // добавляем разделитель
                    tablePractices.addView(headerRowPractices); // добавляем в таблицу строку с заголовками
                    addSeparatorLine(tablePractices); // добавляем разделитель

                    // Заполнение таблицы практики
                    for (String[] practices : practicesList) {
                        TableRow row = new TableRow(getActivity());

                        // Название предмета (не редактируемое)
                        TextView practicesTextView = new TextView(getActivity());
                        practicesTextView.setText(practices[0]);
                        practicesTextView.setPadding(10, 10, 10, 10);
                        row.addView(practicesTextView);

                        // Дата (выбор через DatePickerDialog)
                        EditText dateEditText = new EditText(getActivity());
                        dateEditText.setText(practices[1]);
                        dateEditText.setPadding(10, 10, 10, 10);
                        dateEditText.setFocusable(false); // Отключаем ввод с клавиатуры
                        dateEditText.setOnClickListener(v -> showDatePickerDialog(dateEditText));
                        row.addView(dateEditText);

                        // Количество часов (не редактируемое)
                        TextView hoursTextView = new TextView(getActivity());
                        hoursTextView.setText(practices[2]);
                        hoursTextView.setPadding(10, 10, 10, 10);
                        row.addView(hoursTextView);

                        // Оценка (редактируемое поле)
                        EditText gradeEditText = new EditText(getActivity());
                        gradeEditText.setText(practices[3]);
                        gradeEditText.setPadding(10, 10, 10, 10);
                        gradeEditText.setInputType(InputType.TYPE_CLASS_NUMBER);
                        row.addView(gradeEditText);

                        // Кнопка "Сохранить"
                        Button saveButton = new Button(getActivity());
                        saveButton.setText("Сохранить");
                        saveButton.setOnClickListener(v -> {
                            String updatedDate = dateEditText.getText().toString();
                            String updatedGrade = gradeEditText.getText().toString();

                            updateGradeAndDateInDb_2(practices[0], updatedDate, updatedGrade); // метод для проставления оценки и даты
                        });
                        row.addView(saveButton);

                        tablePractices.addView(row); // добавляем в таблицу строку
                        addSeparatorLine(tablePractices); // добавляем разделитель
                    }
                }

                if (!courseworkList.isEmpty()) {
                    coursework_text_teacher.setText("Курсовые работы");
                    // Добавление заголовков для таблицы курсовых работ
                    TableRow headerRowCoursework = new TableRow(getActivity());
                    String[] courseworkHeaders = {"Название", "Дата", "Кол-во часов", "Оценка", ""};

                    for (String header : courseworkHeaders) {
                        TextView textView = new TextView(getActivity());
                        textView.setText(header);
                        textView.setPadding(10, 10, 10, 10); // Установите отступы для заголовка
                        textView.setBackgroundColor(Color.LTGRAY);
                        headerRowCoursework.addView(textView);
                    }

                    addSeparatorLine(tableCoursework); // добавляем разделитель
                    tableCoursework.addView(headerRowCoursework); // добавляем в таблицу строку с заголовками
                    addSeparatorLine(tableCoursework); // добавляем разделитель

                    // Заполнение таблицы зачетов (с учетом отступов)
                    for (String[] coursework : courseworkList) {
                        TableRow row = new TableRow(getActivity());

                        // Название предмета (не редактируемое)
                        TextView subjectTextView = new TextView(getActivity());
                        subjectTextView.setText(coursework[0]);
                        subjectTextView.setPadding(10, 10, 10, 10);
                        row.addView(subjectTextView);

                        // Дата (выбор через DatePickerDialog)
                        EditText dateEditText = new EditText(getActivity());
                        dateEditText.setText(coursework[1]);
                        dateEditText.setPadding(10, 10, 10, 10);
                        dateEditText.setFocusable(false); // Отключаем ввод с клавиатуры
                        dateEditText.setOnClickListener(v -> showDatePickerDialog(dateEditText));
                        row.addView(dateEditText);

                        // Количество часов (не редактируемое)
                        TextView hoursTextView = new TextView(getActivity());
                        hoursTextView.setText(coursework[2]);
                        hoursTextView.setPadding(10, 10, 10, 10);
                        row.addView(hoursTextView);

                        // Оценка (редактируемое поле)
                        EditText gradeEditText = new EditText(getActivity());
                        gradeEditText.setText(coursework[3]);
                        gradeEditText.setPadding(10, 10, 10, 10);
                        gradeEditText.setInputType(InputType.TYPE_CLASS_NUMBER);
                        row.addView(gradeEditText);

                        // Кнопка "Сохранить"
                        Button saveButton = new Button(getActivity());
                        saveButton.setText("Сохранить");
                        saveButton.setOnClickListener(v -> {
                            String updatedDate = dateEditText.getText().toString();
                            String updatedGrade = gradeEditText.getText().toString();

                            updateGradeAndDateInDb_2(coursework[0], updatedDate, updatedGrade); // метод для проставления оценки и даты
                        });
                        row.addView(saveButton);

                        tableCoursework.addView(row); // добавляем в таблицу строку
                        addSeparatorLine(tableCoursework); // добавляем разделитель
                    }
                }
            }
        });

        return view;
    }

    // метод для выбора даты
    private void showDatePickerDialog(EditText editText) {
        final Calendar calendar = Calendar.getInstance();
        int year = calendar.get(Calendar.YEAR);
        int month = calendar.get(Calendar.MONTH);
        int day = calendar.get(Calendar.DAY_OF_MONTH);

        DatePickerDialog datePickerDialog = new DatePickerDialog(getActivity(),
                (view, selectedYear, selectedMonth, selectedDay) -> {
                    // Форматирование даты в формате "дд.мм.гггг"
                    String formattedDate = String.format("%02d.%02d.%04d", selectedDay, selectedMonth + 1, selectedYear);
                    editText.setText(formattedDate.trim());
                }, year, month, day);

        datePickerDialog.show();
    }

    // метод для обновления бд (экзамены и зачеты)
    private void updateGradeAndDateInDb(String subjectName, String updatedDate, String updatedGrade) {
        String updateQuery = "UPDATE gradebooks " +
                "SET date = ?, grade = ? " +
                "WHERE id_subject = (SELECT id_subject FROM subjects WHERE name_subject = ?)";

        db.execSQL(updateQuery, new String[]{updatedDate, updatedGrade, subjectName});
        Toast.makeText(getActivity(), "Данные обновлены", Toast.LENGTH_SHORT).show();
        db.close();
    }

    // метод для обновления бд (практики и курсовые работы)
    private void updateGradeAndDateInDb_2(String workName, String updatedDate, String updatedGrade) {
        String updateQuery = "UPDATE gradebooks " +
                "SET date = ?, grade = ? " +
                "WHERE id_work = (SELECT id_work FROM works WHERE name_work = ?)";

        db.execSQL(updateQuery, new String[]{updatedDate, updatedGrade, workName});
        Toast.makeText(getActivity(), "Данные обновлены", Toast.LENGTH_SHORT).show();
        db.close();
    }


    // Метод для выполнения SELECT-запроса к group_list
    private ArrayList<String> getSpinnerData() {
        ArrayList<String> spinnerData = new ArrayList<>();
        String selectQuery = "SELECT name_group FROM group_list";

        Cursor cursor = null;
        try {
            db = dbHelper.getWritableDatabase();
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

    // Метод для получения списка студентов по выбранной группе
    @SuppressLint("Range")
    private ArrayList<String> getStudentsForGroup(String groupName) {
        ArrayList<String> studentData = new ArrayList<>();
        db = dbHelper.getWritableDatabase();

        String selectGroup = "SELECT id_group FROM group_list WHERE name_group = ?"; // запрос на получение id_group по названию группы
        Cursor cursorGroup = null;
        Cursor cursorStudents = null;

        try {
            // Получаем id_group для указанной группы
            cursorGroup = db.rawQuery(selectGroup, new String[]{groupName});

            if (cursorGroup.moveToFirst()) {
                @SuppressLint("Range") int groupId = cursorGroup.getInt(cursorGroup.getColumnIndex("id_group"));

                // Используем полученный id_group для запроса списка студентов
                String selectQuery = "SELECT full_name FROM students WHERE id_group = ?";
                cursorStudents = db.rawQuery(selectQuery, new String[]{String.valueOf(groupId)});

                if (cursorStudents.moveToFirst()) {
                    do
                    {
                        studentData.add(cursorStudents.getString(cursorStudents.getColumnIndex("full_name"))); // Получаем имя студента
                    }
                    while (cursorStudents.moveToNext());
                }
            }
        }
        catch (Exception e)
        {
            e.printStackTrace(); // Логируем ошибку
        }
        finally
        {
            if (cursorGroup != null) cursorGroup.close(); // Закрываем курсор для групп
            if (cursorStudents != null) cursorStudents.close(); // Закрываем курсор для студентов
        }

        return studentData;
    }

    // метод для создания разделителя в таблице
    private void addSeparatorLine(TableLayout tableLayout) {
        View line = new View(getActivity());
        line.setLayoutParams(new TableRow.LayoutParams(
                TableRow.LayoutParams.MATCH_PARENT, 1)); // высота 1 пиксель
        line.setBackgroundColor(Color.BLACK); // цвет линии
        tableLayout.addView(line);
    }
}