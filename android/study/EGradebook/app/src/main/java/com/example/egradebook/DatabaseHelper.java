package com.example.egradebook;

import android.content.Context;
import android.database.sqlite.SQLiteDatabase;
import android.database.sqlite.SQLiteOpenHelper;

public class DatabaseHelper extends SQLiteOpenHelper {

    // Имя базы данных и версия
    private static final String DATABASE_NAME = "EGradeBook.db";
    private static final int DATABASE_VERSION = 1;

    public DatabaseHelper(Context context) {
        super(context, DATABASE_NAME, null, DATABASE_VERSION);
    }

    // Вызывается при первом создании базы данных
    @Override
    public void onCreate(SQLiteDatabase db) {
        String createTableUsers = "CREATE TABLE users (" + // таблица пользователи
                "id_user INTEGER PRIMARY KEY AUTOINCREMENT," +
                "phone TEXT NOT NULL," +
                "email TEXT NOT NULL," +
                "password TEXT NOT NULL," +
                "role TEXT NOT NULL)";

        String createTableGroup_list = "CREATE TABLE group_list (" + // таблица группы
                "id_group INTEGER PRIMARY KEY AUTOINCREMENT," +
                "name_group TEXT NOT NULL," +
                "specialization TEXT NOT NULL," +
                "course INTEGER NOT NULL)";

        String createTableTeacher = "CREATE TABLE teachers (" + // таблица преподаватели
                "id_teacher INTEGER PRIMARY KEY AUTOINCREMENT," +
                "full_name TEXT NOT NULL," +
                "id_user INTEGER NOT NULL," +
                "FOREIGN KEY (id_user) REFERENCES users (id_user) ON DELETE CASCADE ON UPDATE CASCADE)";

        String createTableStudents = "CREATE TABLE students (" + // таблица студенты
                "id_student INTEGER PRIMARY KEY AUTOINCREMENT," +
                "full_name TEXT NOT NULL," +
                "id_user INTEGER NOT NULL," +
                "id_group INTEGER NOT NULL," +
                "FOREIGN KEY (id_group) REFERENCES group_list (id_group) ON DELETE CASCADE ON UPDATE CASCADE," +
                "FOREIGN KEY (id_user) REFERENCES users (id_user) ON DELETE CASCADE ON UPDATE CASCADE)";

        String createTableWork = "CREATE TABLE works (" + // таблица с работами (практики и курсовые)
                "id_work INTEGER PRIMARY KEY AUTOINCREMENT," +
                "name_work TEXT NOT NULL," +
                "course INTEGER NOT NULL," +
                "semester INTEGER NOT NULL," +
                "type TEXT NOT NULL," +
                "credit_hours INTEGER," +
                "id_teacher INTEGER NOT NULL," +
                "FOREIGN KEY (id_teacher) REFERENCES teachers (id_teacher) ON DELETE CASCADE ON UPDATE CASCADE)";

        String createTableSubject = "CREATE TABLE subjects (" + // таблица с предметами
                "id_subject INTEGER PRIMARY KEY AUTOINCREMENT," +
                "name_subject TEXT NOT NULL," +
                "final_type TEXT NOT NULL," +
                "course INTEGER NOT NULL," +
                "semester INTEGER NOT NULL," +
                "credit_hours INTEGER," +
                "id_teacher INTEGER NOT NULL," +
                "FOREIGN KEY (id_teacher) REFERENCES teachers (id_teacher) ON DELETE CASCADE ON UPDATE CASCADE)";

        String createTableGradeBook = "CREATE TABLE gradebooks (" + // таблица записей зачетки
                "id_gradebook INTEGER PRIMARY KEY AUTOINCREMENT," +
                "grade INTEGER," +
                "id_student INTEGER NOT NULL," +
                "date TEXT," +
                "id_work INTEGER," +
                "id_subject INTEGER," +
                "FOREIGN KEY (id_student) REFERENCES students (id_student) ON DELETE CASCADE ON UPDATE CASCADE," +
                "FOREIGN KEY (id_work) REFERENCES works (id_work) ON DELETE CASCADE ON UPDATE CASCADE," +
                "FOREIGN KEY (id_subject) REFERENCES subjects (id_subject) ON DELETE CASCADE ON UPDATE CASCADE)";

        db.execSQL(createTableUsers); // Создаём таблицу users
        db.execSQL(createTableGroup_list); // Создаём таблицу group_list
        db.execSQL(createTableTeacher); // Создаём таблицу teachers
        db.execSQL(createTableStudents); // Создаём таблицу students
        db.execSQL(createTableWork); // Создаём таблицу works
        db.execSQL(createTableSubject); // Создаём таблицу subjects
        db.execSQL(createTableGradeBook); // Создаём таблицу gradebooks
    }

    // Вызывается при обновлении базы данных
    @Override
    public void onUpgrade(SQLiteDatabase db, int oldVersion, int newVersion) {
        db.execSQL("DROP TABLE IF EXISTS users"); // Удаляем старую таблицу
        db.execSQL("DROP TABLE IF EXISTS group_list"); // Удаляем старую таблицу
        db.execSQL("DROP TABLE IF EXISTS teachers"); // Удаляем старую таблицу
        db.execSQL("DROP TABLE IF EXISTS students"); // Удаляем старую таблицу
        db.execSQL("DROP TABLE IF EXISTS works"); // Удаляем старую таблицу
        db.execSQL("DROP TABLE IF EXISTS subjects"); // Удаляем старую таблицу
        db.execSQL("DROP TABLE IF EXISTS gradebooks"); // Удаляем старую таблицу
        onCreate(db); // Создаём заново все таблицы
    }
}
