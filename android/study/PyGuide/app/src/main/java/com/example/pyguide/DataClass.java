package com.example.pyguide;

public class DataClass {
    private String dataTitle;
    private int dataDesc;
    private int dataImage;

    public DataClass(String dataTitle, int dataDesc, int dataImage) {
        this.dataTitle = dataTitle;
        this.dataDesc = dataDesc;
        this.dataImage = dataImage;
    }

    public String getDataTitle() {
        return dataTitle;
    }

    public int getDataDesc() {
        return dataDesc;
    }

    public int getDataImage() {
        return dataImage;
    }
}
