package com.example.testapp;

import android.Manifest;
import android.annotation.SuppressLint;
import android.app.DownloadManager;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.content.pm.PackageManager;
import android.net.Uri;
import android.os.AsyncTask;
import android.os.Build;
import android.os.Bundle;
import android.os.Environment;
import android.view.KeyEvent;
import android.view.View;
import android.widget.Button;
import android.widget.RadioButton;
import android.widget.RadioGroup;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.RequiresApi;
import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.app.AppCompatDelegate;
import androidx.core.app.ActivityCompat;
import androidx.core.content.ContextCompat;

import java.io.BufferedReader;
import java.io.DataOutputStream;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.FileOutputStream;
import java.io.FileReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.MalformedURLException;
import java.net.URL;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.Comparator;
import java.util.List;


public class MainActivityFirst extends AppCompatActivity {

    ArrayList<String> lines = new ArrayList<>();
    int length, count_radio, selectedId, count_at;
    String[] name_file;
    String[] array_file;
    String[] link_file;
    String url, number, changeStatus, changeStatus1, filePath;
    StringBuilder sb, sb2;
    List<String> values, values2;
    DownloadManager manager;
    Button btnDownload, btnUpload, btnEdit;
    File file2, f, file_tmp;
    TextView file;
    RadioGroup radioGroup;
    FileInputStream fin;

    MyTask myTask;


    private static final int PERMISSION_CODE = 100;
    String storage = Manifest.permission.WRITE_EXTERNAL_STORAGE;
    String camera = Manifest.permission.CAMERA;

    int count;


    String[] array;
    String[] id;
    String[] name;
    String[] code;
    String[] cell;
    String[] quantity_in_the_package;
    String[] estimated_balance;
    String[] actual_balance;
    int[] count_in_array;

    String id1;
    String code1;
    String cell1;
    String name1;
    String quantity_in_the_package1;
    String estimated_balance1;

    boolean theme = false, exitAfterNextBackPress = false;


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main_first);

        ActivityCompat.requestPermissions(MainActivityFirst.this, new String[]{storage, camera}, PERMISSION_CODE); // запрос разрешений
        // проверка наличия разрешения на использование камеры
        if (ContextCompat.checkSelfPermission(this, Manifest.permission.CAMERA) != PackageManager.PERMISSION_GRANTED & ContextCompat.checkSelfPermission(this, Manifest.permission.WRITE_EXTERNAL_STORAGE) != PackageManager.PERMISSION_GRANTED)
        {
            // разрешение не предоставлено
            Toast.makeText(MainActivityFirst.this, "Разрешения нет", Toast.LENGTH_SHORT).show();
            System.exit(0); // выход из приложения
        }
        else {
            // разрешение предоставлено
            btnDownload = findViewById(R.id.download); //кнопка загрузки
            btnUpload = findViewById(R.id.upload); //кнопка выгрузки
            btnEdit = findViewById(R.id.edit); // кнопка редактирования
            file = findViewById(R.id.textFile); // для вывода названия файла
            radioGroup = findViewById(R.id.radioGroup); // список файлов

            myTask = new MyTask();

            new MyTask().execute(); // сетевое подключение для получения списка файлов
            have_file(); // проверка на наличие файла

            count_at = 0;

            SharedPreferences preferencesTheme = getSharedPreferences("MyPreferencesTheme", MODE_PRIVATE);
            theme = (preferencesTheme.getBoolean("theme", false));
            if(theme)
            {
                AppCompatDelegate.setDefaultNightMode(AppCompatDelegate.MODE_NIGHT_YES);
            }
            else
            {
                AppCompatDelegate.setDefaultNightMode(AppCompatDelegate.MODE_NIGHT_NO);
            }

        }
    }

    @SuppressLint("SetTextI18n")
    public void have_file()
    {
        String path = Environment.getExternalStoragePublicDirectory("Android/data/com.example.testapp/files/invoice/").getAbsolutePath(); //папка с азгрузками
        File directory = new File(path);
        File[] files = directory.listFiles();

        SharedPreferences preferencesFile = getSharedPreferences("MyPreferencesFile", MODE_PRIVATE); // получение названия последнего загруженного файла

        if (files != null) {
            if (files.length > 0) // если есть файлы в загрузках и название последнего скачанного файла не пустое
            {
                for (int l = 0; l < files.length; l++) { //перебор файлов
                    String path2 = path + "/" + preferencesFile.getString("lastFileSend", "") + ".txt";
                    file2 = new File(path2);
                }
            }
            if (file2 != null) {
                f = new File(file2.getPath());
                if (f.exists()) {
                    for (int i = 0; i < count_radio; i++) {
                        radioGroup.getChildAt(i).setEnabled(false);
                    }
                    btnDownload.setEnabled(false);
                    btnUpload.setEnabled(true);
                    btnEdit.setEnabled(true);
                    file.setText("Файл: " + preferencesFile.getString("lastFileSend", ""));
                }
            }
            else
            {
                for (int i = 0; i < count_radio; i++) {
                    radioGroup.getChildAt(i).setEnabled(true);
                }
                btnDownload.setEnabled(false);
                btnUpload.setEnabled(false);
                btnEdit.setEnabled(false);
                file.setText("");
            }
        }
        else
        {
            for (int i = 0; i < count_radio; i++) {
                radioGroup.getChildAt(i).setEnabled(true);
            }
            btnDownload.setEnabled(false);
            btnUpload.setEnabled(false);
            btnEdit.setEnabled(false);
        }

    }

    public void setting(View view) { //активность настроек
        Intent intent = new Intent(MainActivityFirst.this, SettingActivity.class);
        startActivity(intent);
    }

    public void upload(View view) { // кнопка выгрузки

        new UploadFileTask().execute(); // запуск сетевого подключения для выгрузки файла
        new UploadStatus().execute(); // запуск сетевого подключения для изменения статуса в терминале (2)
        file.setText("");

    }

    public void update(View view) {

        btnDownload.setEnabled(false);
        count_at++;
        lines.clear();
        RadioGroup rg = (RadioGroup) findViewById(R.id.radioGroup);
        rg.removeAllViews();
        rg.removeAllViewsInLayout();

        radioGroup.clearCheck();
        new MyTask().execute(); // сетевое подключение для получения списка файлов
        Toast.makeText(MainActivityFirst.this, "Список обновлен", Toast.LENGTH_SHORT).show();
    }



    public class MyTask extends AsyncTask<String, String, String> { // сетевое подключение для списка файлов

        @Override
        protected String doInBackground(String... params) {
            try
            {
                System.out.println("myTask.getStatus()1 " + myTask.getStatus());

                System.out.println("SELID" + selectedId);
                SharedPreferences preferencesList = getSharedPreferences("MyPreferencesList", MODE_PRIVATE);
                if(preferencesList.getString("list", "").length() <= 0)
                {
                    url = "";
                }
                else
                {
                    url = preferencesList.getString("list", "");
                }

                URL yahoo = new URL(url); //получение ссылки
                BufferedReader in = new BufferedReader(new InputStreamReader(yahoo.openStream())); //получение информации с ссылки

                int count = 0;
                String inputLine;

                while ((inputLine = in.readLine()) != null) {
                    count++;
                    lines.add(inputLine.trim().split("\\s+")[0]);
                }
                in.close();
                lines.toString();

                length = 0;
                selectedId = 0;
                name_file = new String[count]; // имя файла
                link_file = new String[count]; // ссылка файла
                array_file = new String[count]; // массив общий
                values = new ArrayList<>(); // сформированный массив из имени и ссылки
                for (int i = 0; i < count; i++) {
                    length = lines.toString().split(",").length;
                    array_file[i] = lines.toString().split(",")[i].replaceAll("\\s", "");
                    name_file[i] = array_file[i].split("\\;")[0].replace("[", " ").replaceAll("\\s", "");
                    link_file[i] = array_file[i].split("\\;")[1].replace("]", " ") + "\n";

                    values.add(array_file[i]); //массив названий файлов и ссылок на их скачивание
                }
                sb = new StringBuilder(128);
                for (String value : values) {
                    if (sb.length() > 0) {
                        sb.append(",");
                    }
                    sb.append(value);
                }
                sb.insert(0, "");


            } catch (Exception ex) {
                // dialog.dismiss();
                ex.printStackTrace();
            }
            return null;
        }

        @Override
        protected void onPostExecute(String makeValue) {

            for (int i = 0; i < length; i++)
            { //создание кнопок с названиями файлов

                RadioButton radioButtonView = new RadioButton(MainActivityFirst.this);
                radioButtonView.setText(name_file[i]);
                RadioGroup radioGroup = (RadioGroup) findViewById(R.id.radioGroup);
                radioGroup.addView(radioButtonView);

                count_radio = radioGroup.getChildCount(); // количетсво кнопок

                have_file();
            }

            radioGroup = (RadioGroup) findViewById(R.id.radioGroup);

            radioGroup.setOnCheckedChangeListener(new RadioGroup.OnCheckedChangeListener() {
                @Override
                public void onCheckedChanged(RadioGroup radioGroup, int i) {
                    if (radioGroup.getCheckedRadioButtonId() != -1)
                    {
                        btnDownload.setEnabled(true);
                    }
                }
            });


            btnDownload.setOnClickListener(new View.OnClickListener() { //по нажатию на кнопку загрузить
                @RequiresApi(api = Build.VERSION_CODES.N)
                @Override
                public void onClick(View v) {
                    RadioButton selectedRadioButton = findViewById(radioGroup.getCheckedRadioButtonId());
                    if (selectedRadioButton != null){
                        for (int i = 0; i < length; i++)
                        { //скачивание выбранного файла
                            if (name_file[i] == selectedRadioButton.getText()) {
                                String lastFile = name_file[i]; // присвоение названию последнего скачанного файла выбранного файла из списка
                                SharedPreferences preferencesFile2 = getSharedPreferences("MyPreferencesFile", MODE_PRIVATE);
                                SharedPreferences.Editor editor = preferencesFile2.edit();
                                editor.putString("lastFileSend", lastFile);
                                editor.apply();

                                String getUrl = link_file[i]; //получение ссылки выбранного файла
                                Uri uri = Uri.parse(getUrl);
                                DownloadManager.Request request = new DownloadManager.Request(uri);
                                request.setDestinationInExternalFilesDir(MainActivityFirst.this, "invoice", name_file[i] + ".txt");
                                manager = (DownloadManager) getSystemService(Context.DOWNLOAD_SERVICE);
                                long reference = manager.enqueue(request);
                                Toast.makeText(MainActivityFirst.this, "Файл загружен", Toast.LENGTH_SHORT).show();

                                String getUrl_tmp = link_file[i]; //получение ссылки выбранного файла
                                Uri uri_tmp = Uri.parse(getUrl_tmp);
                                DownloadManager.Request request_tmp = new DownloadManager.Request(uri_tmp);
                                request_tmp.setDestinationInExternalFilesDir(MainActivityFirst.this, "invoice_tmp", name_file[i] + ".txt");
                                manager = (DownloadManager) getSystemService(Context.DOWNLOAD_SERVICE);
                                long reference_tmp = manager.enqueue(request_tmp);

                                file.setText("Файл: " + name_file[i]);

                                for (int k = 0; k < count_radio; k++) // блокировка скачивания нового файла
                                {
                                    radioGroup.getChildAt(k).setEnabled(false);
                                }
                                btnDownload.setEnabled(false);
                                btnUpload.setEnabled(true);
                                btnEdit.setEnabled(true);

                                String lastFile3 = name_file[i]; // присвоение названию последнего скачанного файла скачанного файла из списка
                                SharedPreferences preferencesFile3 = getSharedPreferences("MyPreferencesFile", MODE_PRIVATE);
                                SharedPreferences.Editor editor3 = preferencesFile3.edit();
                                editor3.putString("lastFileSend", lastFile3);
                                editor3.apply();

                                new DownloadStatus().execute();
                            }
                        }
                    }
                    else
                    {
                        Toast.makeText(MainActivityFirst.this, "Вы не выбрали файл", Toast.LENGTH_SHORT).show();
                    }
                }
            });
            btnEdit.setOnClickListener(new View.OnClickListener() { //нажатие кнопки редактировать
                @RequiresApi(api = Build.VERSION_CODES.N)
                @Override
                public void onClick(View view) {
                    new AnotherTask().doInBackground();

                    Intent intent = new Intent(MainActivityFirst.this, MainActivitySecond.class);
                    intent.putExtra("count", count);
                    intent.putExtra("count_in_array", count_in_array);
                    intent.putExtra("array", array);
                    intent.putExtra("id", id);
                    intent.putExtra("code", code);
                    intent.putExtra("cell", cell);
                    intent.putExtra("name", name);
                    intent.putExtra("quantity_in_the_package", quantity_in_the_package);
                    intent.putExtra("estimated_balance", estimated_balance);
                    intent.putExtra("actual_balance", actual_balance);
                    startActivity(intent); //открытие активности для сканирования и редактирования файла
                }

            });

        }
    }

    public class AnotherTask extends AsyncTask<String, String, String> { // сетевое подключение для списка файлов

        @RequiresApi(api = Build.VERSION_CODES.N)
        @Override
        protected String doInBackground(String... params) {
            try
            {
                System.out.println("SELID2" + selectedId);

            } catch (Exception ex) {
                // dialog.dismiss();
                ex.printStackTrace();
            }

            String path = Environment.getExternalStoragePublicDirectory("Android/data/com.example.testapp/files/invoice/").getAbsolutePath(); //папка с азгрузками
            SharedPreferences preferencesFile = getSharedPreferences("MyPreferencesFile", MODE_PRIVATE);

            String path2 = path + "/" + preferencesFile.getString("lastFileSend", "") + ".txt";
            file2 = new File(path2);
            fin = null;


            String path_tmp = Environment.getExternalStoragePublicDirectory("Android/data/com.example.testapp/files/invoice_tmp/").getAbsolutePath(); //папка с азгрузками
            File directory_tmp = new File(path_tmp);
            File[] files_tmp = directory_tmp.listFiles();
            Arrays.sort(files_tmp, Comparator.comparingLong(File::lastModified));

            for (int l = 0; l < files_tmp.length; l++) { //перебор файлов, в конце последний
                String path_tmp2 = path_tmp + "/" + files_tmp[l].getName();
                file_tmp = new File(path_tmp2);
            }

            try (BufferedReader br = new BufferedReader(new FileReader(file2))) {
                fin = new FileInputStream(file2);
                byte[] bytes = new byte[fin.available()];
                fin.read(bytes);
                String text = new String(bytes);

                count = text.split("\r\n|\r|\n").length; //подсчет строк
                array = new String[count];
                id = new String[count];
                name = new String[count];
                code = new String[count];
                cell = new String[count];
                quantity_in_the_package = new String[count];
                estimated_balance = new String[count];
                actual_balance = new String[count];
                count_in_array = new int[count];

                String line;
                values2 = new ArrayList<>();
                sb2 = new StringBuilder(128);
                int c = 0;

                while ((line = br.readLine()) != null) {

                    String[] parts = line.split(";");

                    id1 = parts[0];
                    id[c] = id1;
                    code1 = parts[1];
                    cell1 = parts[2];
                    name1 = parts[3];
                    quantity_in_the_package1 = parts[4];
                    estimated_balance1 = parts[5];

                    String actual_balance1;
                    if (parts.length == 7) {
                        actual_balance1 = parts[6].trim().replaceAll("\\s+", "").replaceAll("\\n+", "");
                    } else {
                        actual_balance1 = "0";
                    }

                    code[c] = code1;
                    cell[c] = cell1;
                    name[c] = name1;
                    quantity_in_the_package[c] = quantity_in_the_package1;
                    estimated_balance[c] = estimated_balance1;
                    actual_balance[c] = actual_balance1;

                    String main = id1 + ";" + code1 + ";" + cell1 + ";" + name1 + ";" + quantity_in_the_package1 +
                                ";" + estimated_balance1 + ";" + actual_balance1 + "\n";

                    values2.add(main);
                    c++;

                }

                for (String value : values2) {
                    if (sb2.length() > 0) {
                        sb2.append("@");
                    }
                    sb2.append(value);
                }

                sb2.insert(0, "[");
                sb2.append("]");

            } catch (IOException e) {
                e.printStackTrace();
            }

            FileOutputStream fos = null;
            FileOutputStream fos_tmp = null;

            try {
                String txt = (sb2.toString().replace("[", "").replace("]", "").replace("@", "").trim());

                fos = new FileOutputStream(file2);
                fos.write(txt.getBytes()); //сохранение измененного файла

                fos_tmp = new FileOutputStream(file_tmp);
                fos_tmp.write(txt.getBytes()); //сохранение измененного файла

            } catch (IOException ex) {
//                Toast.makeText(MainActivityFirst.this, ex.getMessage(), Toast.LENGTH_SHORT).show();
            }


            return null;
        }

        @Override
        protected void onPostExecute(String makeValue) {
//
        }
    }



    private class UploadFileTask extends AsyncTask<Void, Void, Integer> {
        @Override
        protected Integer doInBackground(Void... voids)
        {
            String path = Environment.getExternalStoragePublicDirectory("Android/data/com.example.testapp/files/invoice/").getAbsolutePath(); //папка с азгрузками

            File directory = new File(path);
            File[] files = directory.listFiles();
            SharedPreferences preferencesFile = getSharedPreferences("MyPreferencesFile", MODE_PRIVATE);

            for (int l = 0; l < files.length; l++) { //перебор файлов, в конце последний
                String path2 = path + "/" + preferencesFile.getString("lastFileSend", "") + ".txt";
                file2 = new File(path2);
            }
            filePath = file2.toString(); //выгружаемый файл

            // URL сервера, обрабатывающего загруженный файл
            SharedPreferences preferencesUpload = getSharedPreferences("MyPreferencesUpload", MODE_PRIVATE);
            String serverUrl;
            if((preferencesUpload.getString("upload", "").length() > 0))
            {
                serverUrl = preferencesUpload.getString("upload", "");
            }
            else
            {
                serverUrl = "";
            }

            // Создание экземпляра FileUploader и вызов метода для загрузки файла
            FileUploader fileUploader = new FileUploader();

            return fileUploader.uploadFile(filePath, serverUrl);
        }

        @Override
        protected void onPostExecute(Integer responseCode) {
            super.onPostExecute(responseCode);

            // Обработка ответа сервера
            if (responseCode == HttpURLConnection.HTTP_OK) {
                // Успешно
                Toast.makeText(MainActivityFirst.this, "Файл успешно загружен.", Toast.LENGTH_SHORT).show();
                String path = Environment.getExternalStoragePublicDirectory("Android/data/com.example.testapp/files/invoice/").getAbsolutePath(); //папка с азгрузками

                File directory = new File(path);
                File[] files = directory.listFiles();

                SharedPreferences preferencesFile = getSharedPreferences("MyPreferencesFile", MODE_PRIVATE);

                for (int l = 0; l < files.length; l++) { //перебор файлов

                    String path2 = path + "/" + preferencesFile.getString("lastFileSend", "") + ".txt";
                    file2 = new File(path2);

                    File fdelete = new File(file2.getPath());

                    if (fdelete.exists()) {
                        if (fdelete.delete()) { // если файл удален, разблокировка скачивания
                            for (int i = 0; i < count_radio; i++) {
                                radioGroup.getChildAt(i).setEnabled(true);
                            }
                            btnDownload.setEnabled(true);
                            btnUpload.setEnabled(false);
                            btnEdit.setEnabled(false);
                        }
                    }
                }

            } else {
                // Возникла ошибка
                Toast.makeText(MainActivityFirst.this, "Ошибка при загрузке файла. Код ответа: " + responseCode, Toast.LENGTH_SHORT).show();
                have_file();
            }
        }
    }

    private static class FileUploader { //отправка файла на сервер
        public int uploadFile(String filePath, String serverUrl) {
            int serverResponseCode = 0;
            String lineEnd = "\r\n";
            String twoHyphens = "--";
            String boundary = "*****";

            try {
                FileInputStream fileInputStream = new FileInputStream(filePath);
                URL url = new URL(serverUrl);
                HttpURLConnection connection = (HttpURLConnection) url.openConnection();

                // Allow Inputs & Outputs
                connection.setDoInput(true);
                connection.setDoOutput(true);
                connection.setUseCaches(false);

                // Set HTTP method to POST
                connection.setRequestMethod("POST");
                connection.setRequestProperty("Connection", "Keep-Alive");
                connection.setRequestProperty("ENCTYPE", "multipart/form-data");
                connection.setRequestProperty("Content-Type", "multipart/form-data;boundary=" + boundary);

                DataOutputStream dos = new DataOutputStream(connection.getOutputStream());

                dos.writeBytes(twoHyphens + boundary + lineEnd);
                dos.writeBytes("Content-Disposition: form-data; name=\"file\";filename=\"" + filePath + "\"" + lineEnd);
                dos.writeBytes(lineEnd);

                int bytesRead, bufferSize;
                byte[] buffer = new byte[1024];
                while ((bytesRead = fileInputStream.read(buffer)) != -1) {
                    dos.write(buffer, 0, bytesRead);
                }

                dos.writeBytes(lineEnd);
                dos.writeBytes(twoHyphens + boundary + twoHyphens + lineEnd);

                serverResponseCode = connection.getResponseCode();

                fileInputStream.close();
                dos.flush();
                dos.close();
            } catch (MalformedURLException e) {
                e.printStackTrace();
            } catch (IOException e) {
                e.printStackTrace();
            }
            return serverResponseCode;
        }
    }

    private class DownloadStatus extends AsyncTask<Void, Void, Integer> { // обновление статуса при загрузке

        @Override
        protected Integer doInBackground(Void... voids) {

            SharedPreferences preferences = getSharedPreferences("MyPreferences", MODE_PRIVATE); // ссылка
            SharedPreferences preferencesFile = getSharedPreferences("MyPreferencesFile", MODE_PRIVATE); // номер
            number = preferencesFile.getString("lastFileSend", ""); // название файла
            if(preferences.getString("changeStatusSend", "").length() <= 0) // если ссылка пустая или не сохранялась при первом запуске, то скрипт не выполняется
            {
                System.out.println();
            }
            else {
                changeStatus = preferences.getString("changeStatusSend", ""); // ссылка изменения статуса при загрузке
                try {
                    URL url = new URL(changeStatus + number); //получение ссылки
                    BufferedReader in = new BufferedReader(new InputStreamReader(url.openStream())); //получение информации с ссылки
                    in.close(); // закрытие
                } catch (MalformedURLException e) {
                    e.printStackTrace();
                } catch (IOException e) {
                    e.printStackTrace();
                }
            }
            return null;
        }
        @Override
        protected void onPostExecute(Integer responseCode) {
            super.onPostExecute(responseCode);
            SharedPreferences preferences = getSharedPreferences("MyPreferences", MODE_PRIVATE); // ссылка
            if(preferences.getString("changeStatusSend", "").length() <= 0) // если ссылка пустая или не сохранялась при первом запуске, то скрипт не выполняется
            {
                Toast.makeText(MainActivityFirst.this, "Пустая ссылка на изменения статуса!", Toast.LENGTH_LONG).show();
            }
        }
    }
    private class UploadStatus extends AsyncTask<Void, Void, Integer> {

        @Override
        protected Integer doInBackground(Void... voids) {

            SharedPreferences preferencesFile = getSharedPreferences("MyPreferencesFile", MODE_PRIVATE); //номер
            number = preferencesFile.getString("lastFileSend", ""); //название файла

            SharedPreferences preferences1 = getSharedPreferences("MyPreferences1", MODE_PRIVATE); //ссылка

            if(preferences1.getString("changeStatusSend1", "").length() <= 0) //если ссылка пуста то скрипт не выполняется
            {
                System.out.println();
            }
            else {
                changeStatus1 = preferences1.getString("changeStatusSend1", ""); // ссылка на изменения статуса выгрузки

                try {
                    URL url = new URL(changeStatus1 + number); //получение ссылки
                    BufferedReader in = new BufferedReader(new InputStreamReader(url.openStream())); //получение информации с ссылки
                    in.close(); //закрытие
                } catch (MalformedURLException e) {
                    e.printStackTrace();
                } catch (IOException e) {
                    e.printStackTrace();
                }
            }
            return null;
        }
        @Override
        protected void onPostExecute(Integer responseCode) {
            super.onPostExecute(responseCode);
            SharedPreferences preferences1 = getSharedPreferences("MyPreferences1", MODE_PRIVATE); //ссылка

            if(preferences1.getString("changeStatusSend1", "").length() <= 0) //если ссылка пуста то скрипт не выполняется
            {
                Toast.makeText(MainActivityFirst.this, "Пустая ссылка на изменения статуса!", Toast.LENGTH_LONG).show();
            }
        }
    }
    @Override
    public boolean onKeyDown(int keyCode, KeyEvent event) {
        if (keyCode == KeyEvent.KEYCODE_BACK) {
            if (exitAfterNextBackPress) {
                this.finishAffinity();
                System.exit(0);
                return true;
            } else {
                Toast.makeText(MainActivityFirst.this, "Нажмите ещё раз для выхода", Toast.LENGTH_SHORT).show();
                exitAfterNextBackPress = true;
                return true;
            }
        }
        return super.onKeyDown(keyCode, event);
    }
}