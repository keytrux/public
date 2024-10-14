package com.example.testapp;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.app.AppCompatDelegate;

import android.annotation.SuppressLint;
import android.app.AlertDialog;
import android.content.DialogInterface;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.preference.PreferenceManager;
import android.text.Editable;
import android.text.TextUtils;
import android.text.TextWatcher;
import android.view.View;
import android.widget.CompoundButton;
import android.widget.EditText;
import android.widget.Switch;
import android.widget.Toast;


public class SettingActivity extends AppCompatActivity {

    EditText writeUrl, changeStatus, changeStatus1, upload, LastFile, id;
    Switch sw, swTheme;
    boolean keyboard = false;
    boolean theme = false;

    public static final String TEXT_STATUS = ""; // дефолтная ссылка для изменения статуса заргузки
    public static final String TEXT_STATUS1 = ""; // дефолтная ссылка для изменения статуса выгрузки
    public static final String TEXT_URL = ""; // дефолтная ссылка для получения списка файлов
    public static final String TEXT_UPLOAD = ""; // дефолтная ссылка для выгрузки файла
    public static final String TEXT_ID = "1"; // дефолтный идентификатор терминала
    public static final String TEXT_PATH = "Android/data/com.example.testapp/files/invoice/";


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_setting);

        changeStatus = findViewById(R.id.writeChangeStatus); // ссылка статуса
        changeStatus1 = findViewById(R.id.writeChangeStatus1); // ссылка статуса 1
        upload = findViewById(R.id.writeUpload); // ссылка выгрузки
        LastFile = findViewById(R.id.writeLastFile); // название последнего скачанного файла
        writeUrl =  findViewById(R.id.writeUrl); // ссылка на получение списка файлов
        id = findViewById(R.id.writeId); // идентификатор терминала

        System.out.println(keyboard);
        id.setShowSoftInputOnFocus(keyboard);
        changeStatus.setShowSoftInputOnFocus(keyboard);
        changeStatus1.setShowSoftInputOnFocus(keyboard);
        upload.setShowSoftInputOnFocus(keyboard);
        LastFile.setShowSoftInputOnFocus(keyboard);
        writeUrl.setShowSoftInputOnFocus(keyboard);


        sw = findViewById(R.id.checkKeyboard); // switch включения клавиатуры
        swTheme = findViewById(R.id.checkTheme); // switch переключения темы

        sw.setOnCheckedChangeListener(new CompoundButton.OnCheckedChangeListener() {
            public void onCheckedChanged(CompoundButton buttonView, boolean isChecked) {
                if(isChecked == true)
                {
                    keyboard = true;
                    System.out.println("true");

                    boolean keyboardSend = keyboard;
                    SharedPreferences preferences = getSharedPreferences("MyPreferencesKey", MODE_PRIVATE);
                    SharedPreferences.Editor editor = preferences.edit();
                    editor.putBoolean("key", keyboardSend);
                    editor.apply();
                    changeStatus.setShowSoftInputOnFocus(keyboard);
                    changeStatus1.setShowSoftInputOnFocus(keyboard);
                    upload.setShowSoftInputOnFocus(keyboard);
                    LastFile.setShowSoftInputOnFocus(keyboard);
                    writeUrl.setShowSoftInputOnFocus(keyboard);
                    id.setShowSoftInputOnFocus(keyboard);
                }
                else
                {
                    keyboard = false;
                    System.out.println("false");
                    boolean keyboardSend = keyboard;
                    SharedPreferences preferences = getSharedPreferences("MyPreferencesKey", MODE_PRIVATE);
                    SharedPreferences.Editor editor = preferences.edit();
                    editor.putBoolean("key", keyboardSend);
                    editor.apply();
                    changeStatus.setShowSoftInputOnFocus(keyboard);
                    changeStatus1.setShowSoftInputOnFocus(keyboard);
                    upload.setShowSoftInputOnFocus(keyboard);
                    LastFile.setShowSoftInputOnFocus(keyboard);
                    writeUrl.setShowSoftInputOnFocus(keyboard);
                    id.setShowSoftInputOnFocus(keyboard);
                }
            }
        });

        SharedPreferences preferencesKey = getSharedPreferences("MyPreferencesKey", MODE_PRIVATE);
        keyboard = (preferencesKey.getBoolean("key", false));
        sw.setChecked(keyboard);

        swTheme.setOnCheckedChangeListener(new CompoundButton.OnCheckedChangeListener() {
            public void onCheckedChanged(CompoundButton buttonView, boolean isChecked) {
                if(isChecked == true)
                {
                    theme = true;
                    System.out.println("true");

                    boolean themeSend = theme;
                    SharedPreferences preferencesTheme = getSharedPreferences("MyPreferencesTheme", MODE_PRIVATE);
                    SharedPreferences.Editor editorTheme = preferencesTheme.edit();
                    editorTheme.putBoolean("theme", themeSend);
                    editorTheme.apply();
                    AppCompatDelegate.setDefaultNightMode(AppCompatDelegate.MODE_NIGHT_YES);
                }
                else
                {
                    theme = false;
                    System.out.println("false");
                    boolean themeSend = theme;
                    SharedPreferences preferencesTheme = getSharedPreferences("MyPreferencesTheme", MODE_PRIVATE);
                    SharedPreferences.Editor editorTheme = preferencesTheme.edit();
                    editorTheme.putBoolean("theme", themeSend);
                    editorTheme.apply();
                    AppCompatDelegate.setDefaultNightMode(AppCompatDelegate.MODE_NIGHT_NO);
                }
            }
        });

        SharedPreferences preferencesTheme = getSharedPreferences("MyPreferencesTheme", MODE_PRIVATE);
        theme = (preferencesTheme.getBoolean("theme", false));
        swTheme.setChecked(theme);

        if(theme)
        {
            AppCompatDelegate.setDefaultNightMode(AppCompatDelegate.MODE_NIGHT_YES);
        }
        else
        {
            AppCompatDelegate.setDefaultNightMode(AppCompatDelegate.MODE_NIGHT_NO);
        }

        textChange(); // изменение текста

        LastFile.setFocusable(false);

        //получение ссылки изменения статуса
        String changeStatusSend = changeStatus.getText().toString();
        SharedPreferences preferences = getSharedPreferences("MyPreferences", MODE_PRIVATE);
        SharedPreferences.Editor editor = preferences.edit();
        editor.putString("changeStatusSend", changeStatusSend);
        editor.apply();

        //получение ссылки изменения статуса 1
        String changeStatusSend1 = changeStatus1.getText().toString();
        SharedPreferences preferences1 = getSharedPreferences("MyPreferences1", MODE_PRIVATE);
        SharedPreferences.Editor editor1 = preferences1.edit();
        editor1.putString("changeStatusSend1", changeStatusSend1);
        editor1.apply();

        //получение ссылки для получения списка файлов
        String list = writeUrl.getText().toString();
        SharedPreferences preferencesList = getSharedPreferences("MyPreferencesList", MODE_PRIVATE);
        SharedPreferences.Editor editorList = preferencesList.edit();
        editorList.putString("list", list);
        editorList.apply();

        //получение ссылки для выгрузки файла
        String url_upload = upload.getText().toString();
        SharedPreferences preferencesUpload = getSharedPreferences("MyPreferencesUpload", MODE_PRIVATE);
        SharedPreferences.Editor editorUpload = preferencesUpload.edit();
        editorUpload.putString("upload", url_upload);
        editorUpload.apply();

        //получение id
        String textId = id.getText().toString();
        SharedPreferences preferencesId = getSharedPreferences("MyPreferencesId", MODE_PRIVATE);
        SharedPreferences.Editor editorId = preferencesId.edit();
        editorId.putString("textId", textId);
        editorId.apply();

//        //получение пути к файлу
//        String textPath = path.getText().toString();
//        SharedPreferences preferencesPath = getSharedPreferences("MyPreferencesPath", MODE_PRIVATE);
//        SharedPreferences.Editor editorPath = preferencesPath.edit();
//        editorPath.putString("path", textPath);
//        editorPath.apply();

        //присвоение EditText название последнего скачанного файла
        SharedPreferences preferencesFile = getSharedPreferences("MyPreferencesFile", MODE_PRIVATE);
        LastFile.setText(preferencesFile.getString("lastFileSend", ""));
    }
    public void textChange() //изменение текста
    {
        //РАБОТАЕТ СОХРАНЯЕТ ТЕКСТ
        SharedPreferences preferencesId = getSharedPreferences("MyPreferencesId", MODE_PRIVATE);

        final SharedPreferences pref = PreferenceManager.getDefaultSharedPreferences(this);
        changeStatus.setText(pref.getString(TEXT_STATUS, "" + preferencesId.getString("textId", "") + "&number="));
        changeStatus.addTextChangedListener(new TextWatcher() {
            @Override
            public void beforeTextChanged(CharSequence s, int start, int count, int after) {

            }

            @Override
            public void onTextChanged(CharSequence s, int start, int before, int count) {

            }

            @Override
            public void afterTextChanged(Editable s) {

                String text=changeStatus.getText().toString().trim();
                if (TextUtils.isEmpty(text)){
                    changeStatus.setError("Пустая ссылка на изменения статуса!");
                }
                else {
                    pref.edit().putString(TEXT_STATUS, s.toString()).commit();

                    String changeStatusSend = changeStatus.getText().toString();
                    SharedPreferences preferences = getSharedPreferences("MyPreferences", MODE_PRIVATE);
                    SharedPreferences.Editor editor = preferences.edit();
                    editor.putString("changeStatusSend", changeStatusSend);
                    editor.apply();
                }
            }
        });

        changeStatus1.setText(pref.getString(TEXT_STATUS1, ""+ preferencesId.getString("textId", "") +"&number="));
        changeStatus1.addTextChangedListener(new TextWatcher() {
            @Override
            public void beforeTextChanged(CharSequence s, int start, int count, int after) {

            }

            @Override
            public void onTextChanged(CharSequence s, int start, int before, int count) {

            }

            @Override
            public void afterTextChanged(Editable s) {

                String text1=changeStatus1.getText().toString().trim();
                if(TextUtils.isEmpty(text1))
                {
                    changeStatus1.setError("Пустая ссылка на изменения статуса!");
                }
                else {
                    pref.edit().putString(TEXT_STATUS1, s.toString()).commit();

                    String changeStatusSend1 = changeStatus1.getText().toString();
                    SharedPreferences preferences1 = getSharedPreferences("MyPreferences1", MODE_PRIVATE);
                    SharedPreferences.Editor editor1 = preferences1.edit();
                    editor1.putString("changeStatusSend1", changeStatusSend1);
                    editor1.apply();
                }
            }
        });
        writeUrl.setText(pref.getString(TEXT_URL, ""));
        writeUrl.addTextChangedListener(new TextWatcher() {
            @Override
            public void beforeTextChanged(CharSequence charSequence, int i, int i1, int i2) {

            }

            @Override
            public void onTextChanged(CharSequence charSequence, int i, int i1, int i2) {

            }

            @Override
            public void afterTextChanged(Editable editable) {
                String textList=writeUrl.getText().toString().trim();
                if(TextUtils.isEmpty(textList))
                {
                    writeUrl.setError("Пустая ссылка на изменения статуса!");
                }
                else {
                    pref.edit().putString(TEXT_URL, editable.toString()).commit();

                    String list = writeUrl.getText().toString();
                    SharedPreferences preferencesList = getSharedPreferences("MyPreferencesList", MODE_PRIVATE);
                    SharedPreferences.Editor editorList = preferencesList.edit();
                    editorList.putString("list", list);
                    editorList.apply();
                }
            }
        });
        upload.setText(pref.getString(TEXT_UPLOAD, ""));
        upload.addTextChangedListener(new TextWatcher() {
            @Override
            public void beforeTextChanged(CharSequence charSequence, int i, int i1, int i2) {

            }

            @Override
            public void onTextChanged(CharSequence charSequence, int i, int i1, int i2) {

            }

            @Override
            public void afterTextChanged(Editable editable) {
                String textUpload=upload.getText().toString().trim();
                if(TextUtils.isEmpty(textUpload))
                {
                    upload.setError("Пустая ссылка на выгрузку файла!");
                }
                else {
                    pref.edit().putString(TEXT_UPLOAD, editable.toString()).commit();

                    String url_upload = upload.getText().toString();
                    SharedPreferences preferencesUpload = getSharedPreferences("MyPreferencesUpload", MODE_PRIVATE);
                    SharedPreferences.Editor editorUpload = preferencesUpload.edit();
                    editorUpload.putString("list", url_upload);
                    editorUpload.apply();
                }
            }
        });

        id.setText(pref.getString(TEXT_ID, "1"));
        id.addTextChangedListener(new TextWatcher() {
            @Override
            public void beforeTextChanged(CharSequence s, int start, int count, int after) {

            }

            @Override
            public void onTextChanged(CharSequence s, int start, int before, int count) {

            }

            @Override
            public void afterTextChanged(Editable s) {

                String text1=id.getText().toString().trim();
                if(TextUtils.isEmpty(text1))
                {
                    id.setError("Пустое поле!");
                }
                else {
                    pref.edit().putString(TEXT_ID, s.toString()).commit();

                    String textId = id.getText().toString();
                    SharedPreferences preferencesId = getSharedPreferences("MyPreferencesId", MODE_PRIVATE);
                    SharedPreferences.Editor editorId = preferencesId.edit();
                    editorId.putString("textId", textId);
                    editorId.apply();

                    changeStatus.setText(""+ preferencesId.getString("textId", "") +"&number=");
                    changeStatus1.setText(""+ preferencesId.getString("textId", "") +"&number=");
                }
            }
        });
//        path.setText(pref.getString(TEXT_PATH, "Android/data/com.example.testapp/files/invoice/"));
//        path.addTextChangedListener(new TextWatcher() {
//            @Override
//            public void beforeTextChanged(CharSequence charSequence, int i, int i1, int i2) {
//
//            }
//
//            @Override
//            public void onTextChanged(CharSequence charSequence, int i, int i1, int i2) {
//
//            }
//
//            @Override
//            public void afterTextChanged(Editable editable) {
//                String textP=path.getText().toString().trim();
//                if(TextUtils.isEmpty(textP))
//                {
//                    path.setError("Пустая ссылка на выгрузку файла!");
//                }
//                else {
//                    pref.edit().putString(TEXT_PATH, editable.toString()).commit();
//
//                    String textPath = path.getText().toString();
//                    SharedPreferences preferencesPath = getSharedPreferences("MyPreferencesPath", MODE_PRIVATE);
//                    SharedPreferences.Editor editorPath = preferencesPath.edit();
//                    editorPath.putString("path", textPath);
//                    editorPath.apply();
//                }
//            }
//        });
    }

    public void saveSetting(View view) { //сохранение настроек

        changeStatus.setShowSoftInputOnFocus(keyboard);
        changeStatus1.setShowSoftInputOnFocus(keyboard);
        upload.setShowSoftInputOnFocus(keyboard);
        LastFile.setShowSoftInputOnFocus(keyboard);
        writeUrl.setShowSoftInputOnFocus(keyboard);
        id.setShowSoftInputOnFocus(keyboard);
        if(theme)
        {
            AppCompatDelegate.setDefaultNightMode(AppCompatDelegate.MODE_NIGHT_YES);
        }
        else
        {
            AppCompatDelegate.setDefaultNightMode(AppCompatDelegate.MODE_NIGHT_NO);
        }
        String text = changeStatus.getText().toString().trim();
        String text1 = changeStatus1.getText().toString().trim();
        String textList = writeUrl.getText().toString().trim();
        String textUpload = upload.getText().toString().trim();
        String textId = id.getText().toString().trim();
        //проверки на пустые поля
        if (TextUtils.isEmpty(text)){
            changeStatus.setError("Пустая ссылка на изменения статуса!");
        }
        else if(TextUtils.isEmpty(text1))
        {
            changeStatus1.setError("Пустая ссылка на изменения статуса!");
        }
        else if(TextUtils.isEmpty(textList))
        {
            writeUrl.setError("Пустая ссылка для загрузки накладной!");
        }
        else if(TextUtils.isEmpty(textUpload))
        {
            upload.setError("Пустая ссылка для выгрузки файла!");
        }
        else if(TextUtils.isEmpty(textId))
        {
            id.setError("Пустой идентификатор терминала!");
        }
        else {
            AlertDialog.Builder builder = new AlertDialog.Builder(SettingActivity.this);
            builder.setMessage("Для сохранения приложение перезапустится")
                    .setCancelable(false)
                    .setPositiveButton("OK", new DialogInterface.OnClickListener() {
                        public void onClick(DialogInterface dialog, int id) {
                            textChange(); //изменение сохранение текста
                            System.exit(0);
                        }
                    });
            AlertDialog alert = builder.create();
            alert.show();
        }
    }

    @SuppressLint("SetTextI18n")
    public void resetSetting(View view) { // сброс настроек
        writeUrl.setText("");
        upload.setText("");
        changeStatus.setText("");
        changeStatus1.setText("");
        id.setText("1");
        textChange();

        keyboard = false;
        boolean keyboardSend = keyboard;
        SharedPreferences preferences = getSharedPreferences("MyPreferencesKey", MODE_PRIVATE);
        SharedPreferences.Editor editor = preferences.edit();
        editor.putBoolean("key", keyboardSend);
        editor.apply();
        sw.setChecked(keyboard);
        changeStatus.setShowSoftInputOnFocus(keyboard);
        changeStatus1.setShowSoftInputOnFocus(keyboard);
        upload.setShowSoftInputOnFocus(keyboard);
        LastFile.setShowSoftInputOnFocus(keyboard);
        writeUrl.setShowSoftInputOnFocus(keyboard);

        theme = false;
        boolean ThemeSend = theme;
        SharedPreferences preferencesTheme = getSharedPreferences("MyPreferencesTheme", MODE_PRIVATE);
        SharedPreferences.Editor editorTheme = preferencesTheme.edit();
        editorTheme.putBoolean("theme", ThemeSend);
        editorTheme.apply();
        swTheme.setChecked(theme);
        AppCompatDelegate.setDefaultNightMode(AppCompatDelegate.MODE_NIGHT_NO);

        Toast.makeText(SettingActivity.this, "Настройки сброшены", Toast.LENGTH_SHORT).show();
    }


}