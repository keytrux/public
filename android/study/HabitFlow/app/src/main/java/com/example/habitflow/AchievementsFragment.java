package com.example.habitflow;

import android.content.Context;
import android.content.SharedPreferences;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.os.Bundle;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.fragment.app.Fragment;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;
import java.util.ArrayList;
import java.util.List;

public class AchievementsFragment extends Fragment {

    DatabaseHelper dbHelper;
    RecyclerView recyclerView;
    AchievementsAdapter adapter;
    List<Achievement> achievements;

    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {

        View view = inflater.inflate(R.layout.fragment_achievements, container, false);
        dbHelper = new DatabaseHelper(getActivity());
        // Инициализация RecyclerView
        recyclerView = view.findViewById(R.id.recyclerView_achievements);
        recyclerView.setLayoutManager(new LinearLayoutManager(requireContext()));
        // Загрузка списка достижений
        achievements = loadAchievements();
        // Инициализация адаптера AchievementAdapter
        adapter = new AchievementsAdapter(getActivity(), achievements);
        recyclerView.setAdapter(adapter);

        return view;
    }

    @Override
    public void onResume() {
        super.onResume();
        // Перезагрузка привычек после добавления
        achievements.clear();
        achievements.addAll(loadAchievements());
        adapter.notifyDataSetChanged();
    }

    private List<Achievement> loadAchievements() {
        List<Achievement> achievementList = new ArrayList<>();

        SQLiteDatabase db = null;
        Cursor cursor = null;

        try {
            // Открытие базы данных
            db = dbHelper.getReadableDatabase();
            // Получаем ID текущего пользователя
            SharedPreferences preferences = requireActivity().getSharedPreferences("UserPrefs", Context.MODE_PRIVATE);
            int userId = preferences.getInt("id_user", 0);
            if (userId == -1) {
                Log.d("HabitsFragment", "Не найден id_user, список привычек пуст.");
                return achievementList; // Возвращаем пустой список
            }
            // Выполнение запроса: выбрать достижения, принадлежащие текущему пользователю
            String query = "SELECT id_achievement, name, description, icon, date FROM achievements WHERE id_user = ?";
            cursor = db.rawQuery(query, new String[]{String.valueOf(userId)});

            // Обрабатываем результаты запроса
            if (cursor.moveToFirst()) {
                do {
                    int id_achievement = cursor.getInt(cursor.getColumnIndexOrThrow("id_achievement"));
                    String name = cursor.getString(cursor.getColumnIndexOrThrow("name"));
                    String description = cursor.getString(cursor.getColumnIndexOrThrow("description"));
                    String icon = cursor.getString(cursor.getColumnIndexOrThrow("icon"));
                    String date = cursor.getString(cursor.getColumnIndexOrThrow("date"));

                    // Создаем объект Achievement и добавляем в список
                    achievementList.add(new Achievement(id_achievement, name, description, icon, date));
                } while (cursor.moveToNext());
            }
        } catch (Exception e) {
            e.printStackTrace();
            Log.e("AchievementsFragment", "Ошибка при загрузке достижений: " + e.getMessage());
        } finally {
            // Закрываем курсор и базу данных, чтобы избежать утечек
            if (cursor != null) cursor.close();
            if (db != null) db.close();
        }

        // Возвращаем список загруженных достижений
        return achievementList;
    }
}