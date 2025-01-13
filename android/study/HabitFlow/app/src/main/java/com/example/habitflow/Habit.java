package com.example.habitflow;

public class Habit {
    private int id_habit;
    private String name;
    private String icon;
    private int streak;
    private int goal;
    private String created;
    private String lastCompletedDate;

    public Habit(int id_habit, String name, String icon, int streak, int goal, String created, String lastCompletedDate) {
        this.id_habit = id_habit;
        this.name = name;
        this.icon = icon;
        this.streak = streak;
        this.goal = goal;
        this.created = created;
        this.lastCompletedDate = lastCompletedDate;
    }
    // Геттеры и сеттеры
    public int getId() {
        return id_habit;
    }

    public String getName() {
        return name;
    }

    public String getIcon() {
        return icon;
    }

    public int getStreak() {
        return streak;
    }

    public int getGoal() {
        return goal;
    }

    public String getCreated() {
        return created;
    }

    public String getLastCompletedDate() {
        return lastCompletedDate;
    }

    public void setLastCompletedDate(String lastCompletedDate) {
        this.lastCompletedDate = lastCompletedDate;
    }

    public void incrementStreak() {
        this.streak++;
    }
}