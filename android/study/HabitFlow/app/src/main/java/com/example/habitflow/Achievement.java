package com.example.habitflow;

public class Achievement {
    private int id_achievement;
    private String name;
    private String description;
    private String icon;
    private String date;

    public Achievement(int id_achievement, String name, String description, String icon, String date) {
        this.id_achievement = id_achievement;
        this.name = name;
        this.description = description;
        this.icon = icon;
        this.date = date;
    }

    public int getId() {
        return id_achievement;
    }

    public String getName() {
        return name;
    }

    public String getDescription() {
        return description;
    }

    public String getIcon() {
        return icon;
    }

    public String getDate() {
        return date;
    }
}
