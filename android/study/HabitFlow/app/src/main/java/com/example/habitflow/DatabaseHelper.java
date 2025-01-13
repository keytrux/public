package com.example.habitflow;

import android.content.Context;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.database.sqlite.SQLiteOpenHelper;

public class DatabaseHelper extends SQLiteOpenHelper {
    // Имя базы данных и версия
    private static final String DATABASE_NAME = "HabitFlow.db";
    private static final int DATABASE_VERSION = 1;

    public static final String TABLE_HABITS = "habits";
    public static final String COLUMN_NAME = "name";
    public static final String COLUMN_STREAK = "streak";
    public static final String COLUMN_GOAL = "goal";
    public static final String COLUMN_LAST_COMPLETED_DATE = "last_completed_date";

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
                "name TEXT NOT NULL," +
                "created TEXT NOT NULL)";

        String createTableHabits = "CREATE TABLE habits (" + // таблица привычки
                "id_habit INTEGER PRIMARY KEY AUTOINCREMENT," +
                "name TEXT NOT NULL," +
                "icon TEXT," +
                "created TEXT NOT NULL," +
                "goal INTEGER NOT NULL," +
                "streak INTEGER," +
                "last_completed_date TEXT," +
                "id_user INTEGER NOT NULL," +
                "FOREIGN KEY (id_user) REFERENCES users (id_user) ON DELETE CASCADE ON UPDATE CASCADE)";

        String createTableAchievements = "CREATE TABLE achievements (" + // таблица достижений пользователя
                "id_achievement INTEGER PRIMARY KEY AUTOINCREMENT," +
                "name TEXT NOT NULL," +
                "description TEXT," +
                "icon TEXT," +
                "date TEXT NOT NULL," +
                "id_user INTEGER NOT NULL," +
                "id_habit INTEGER NOT NULL," +
                "FOREIGN KEY (id_habit) REFERENCES habits (id_habit) ON DELETE CASCADE ON UPDATE CASCADE," +
                "FOREIGN KEY (id_user) REFERENCES users (id_user) ON DELETE CASCADE ON UPDATE CASCADE)";

        db.execSQL(createTableUsers); // Создаём таблицу Users
        db.execSQL(createTableHabits); // Создаём таблицу Habits
        db.execSQL(createTableAchievements); // Создаём таблицу Achievements

    }

    // Вызывается при обновлении базы данных
    @Override
    public void onUpgrade(SQLiteDatabase db, int oldVersion, int newVersion) {
        db.execSQL("DROP TABLE IF EXISTS users"); // Удаляем старую таблицу
        db.execSQL("DROP TABLE IF EXISTS habits"); // Удаляем старую таблицу
        db.execSQL("DROP TABLE IF EXISTS achievements"); // Удаляем старую таблицу
        onCreate(db); // Создаём заново все таблицы
    }

    public Cursor getAllHabits(int idUser) {
        SQLiteDatabase db = this.getReadableDatabase();
        String selection = "id_user = ?";
        String[] selectionArgs = { String.valueOf(idUser) };
        return db.query(TABLE_HABITS, null, selection, selectionArgs, null, null, null);
    }
}