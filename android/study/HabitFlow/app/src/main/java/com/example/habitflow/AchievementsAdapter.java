package com.example.habitflow;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;
import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.List;

public class AchievementsAdapter extends RecyclerView.Adapter<AchievementsAdapter.ViewHolder> {

    private final List<Achievement> achievements;
    private final Context context; // Храним контекст
    private final DatabaseHelper dbHelper; // Переменная для работы с базой данных

    public AchievementsAdapter(Context context, List<Achievement> achievements) {
        this.context = context; // Передаем контекст
        this.achievements = achievements;
        this.dbHelper = new DatabaseHelper(context); // Инициализируем DatabaseHelper
    }

    @NonNull
    @Override
    public AchievementsAdapter.ViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(parent.getContext())
                .inflate(R.layout.achivment_item, parent, false);

        return new AchievementsAdapter.ViewHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull AchievementsAdapter.ViewHolder holder, int position) {
        Achievement achievement = achievements.get(position);

        holder.achievementName.setText(achievement.getName());

        holder.achievementDescription.setText(achievement.getDescription());

        holder.achievementDate.setText(achievement.getDate());

        String iconName = achievement.getIcon(); // Это строка с именем иконки из базы данных
        int iconResId = holder.itemView.getContext().getResources()
                .getIdentifier(iconName, "drawable", holder.itemView.getContext().getPackageName());

        if (iconResId != 0) {
            holder.achievementIcon.setImageResource(iconResId); // Устанавливаем картинку
        } else {
            // Если иконка не найдена, показываем дефолтную иконку
            holder.achievementIcon.setImageResource(R.drawable.default_icon_ach);
        }
    }

    @Override
    public int getItemCount() {
        return achievements.size();
    }

    public static class ViewHolder extends RecyclerView.ViewHolder {
        private final TextView achievementName;
        private final TextView achievementDescription;
        private final TextView achievementDate;
        private final ImageView achievementIcon;

        public ViewHolder(@NonNull View itemView) {
            super(itemView);
            achievementName = itemView.findViewById(R.id.achievementName);
            achievementDescription = itemView.findViewById(R.id.achievementDescription);
            achievementDate = itemView.findViewById(R.id.achievementDate);
            achievementIcon = itemView.findViewById(R.id.achievementIcon);
        }
    }
}