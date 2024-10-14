package com.example.testapp;

import static java.util.Arrays.binarySearch;

import androidx.activity.result.ActivityResultLauncher;
import androidx.annotation.RequiresApi;
import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.app.AppCompatDelegate;

import android.annotation.SuppressLint;
import android.app.AlertDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.SharedPreferences;
import android.graphics.Color;
import android.os.Build;
import android.os.Bundle;
import android.os.Environment;
import android.text.Editable;
import android.text.TextWatcher;
import android.view.KeyEvent;
import android.view.View;
import android.view.animation.Animation;
import android.view.animation.AnimationUtils;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;
import android.widget.Toast;

import com.google.android.gms.common.util.ArrayUtils;
import com.journeyapps.barcodescanner.ScanContract;
import com.journeyapps.barcodescanner.ScanOptions;

import java.io.File;
import java.io.FileInputStream;
import java.io.FileOutputStream;
import java.io.IOException;
import java.text.DecimalFormat;
import java.text.NumberFormat;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.Collections;
import java.util.Comparator;
import java.util.List;



public class MainActivitySecond extends AppCompatActivity {

    String a, total2;
    Button btn_scan, btn_back, btn_next, btn_save;
    TextView openTextFile, textError, writeTextCode, writeName, writeCell, textEstimated_balance, file;
    EditText writeCode, writeActual_balance, amountEstimated_balance;
    FileInputStream fin;
    File file2, file_tmp;
    int count_array, count_array2, inum, cou = 0, cou1 = 0, af, af2, count;
    double b, c, ab, ab1, ab2;
    boolean count_next = true, count_scan = true, keyboard = false, theme = false;
    String[] id; //массив для ячеек
    String[] actual_balance; //массив для фактического
    String[] write_estimated_balance;
    String[] cell;
    String[] name;
    String[] code;
    String[] quantity_in_the_package;
    String[] estimated_balance;
    int[] count_in_array;

    String[] name_open;


    @SuppressLint("SetTextI18n")
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main_second);

        btn_scan = findViewById(R.id.btn_scan); // кнопка сканирования (камера)
        btn_back = findViewById(R.id.btn_back); // кнопка предыдущая запись
        btn_next = findViewById(R.id.btn_next); // кнопка следующая запись
        btn_save = findViewById(R.id.save_text); // кнопка сохранения

        textError = findViewById(R.id.textError); // текст ошибок
        writeTextCode = findViewById(R.id.writeTextCode); // ввод кода
        openTextFile = findViewById(R.id.text); // текст файла
        file = findViewById(R.id.file); // название редатируемого файла

        writeName = findViewById(R.id.writeName); // вывод названия продукта
        writeCode = findViewById(R.id.writeCode); // вывод кода
        writeActual_balance = findViewById(R.id.writeActual_balance); // ввод фактического остатка ("1")
        writeCell = findViewById(R.id.writeCell); // вывод линии продукта
        textEstimated_balance = findViewById(R.id.textEstimated_balance2); // вывод расчетного остатка
        amountEstimated_balance = findViewById(R.id.amountEstimated_balance); //вывод суммы набранного

        SharedPreferences preferencesKey = getSharedPreferences("MyPreferencesKey", MODE_PRIVATE);
        keyboard = (preferencesKey.getBoolean("key", false));

        SharedPreferences preferencesTheme = getSharedPreferences("MyPreferencesTheme", MODE_PRIVATE);
        theme = (preferencesTheme.getBoolean("theme", false));

        if(theme)
        {
            AppCompatDelegate.setDefaultNightMode(AppCompatDelegate.MODE_NIGHT_YES);
            writeCode.setTextColor(Color.parseColor("#333333"));
            writeCode.setHintTextColor(Color.parseColor("#333333"));
        }
        else
        {
            AppCompatDelegate.setDefaultNightMode(AppCompatDelegate.MODE_NIGHT_NO);
        }


        amountEstimated_balance.setFocusable(false);

        writeCode.requestFocus(); // фокус на вводе кода
        writeCode.setShowSoftInputOnFocus(keyboard);

        writeCode.addTextChangedListener(new CurrencyTextWatcher()); // вызов метода при изменении ввода кода

        writeActual_balance.setShowSoftInputOnFocus(keyboard);
        writeActual_balance.addTextChangedListener(new CurrencyTextWatcher_balance()); // вызов метода при изменении фактического остатка

        count_array = 0;

        SharedPreferences preferencesFile = getSharedPreferences("MyPreferencesFile", MODE_PRIVATE);
        file.setText("Файл: " + preferencesFile.getString("lastFileSend", ""));

        Intent intent = getIntent();
        count = intent.getIntExtra("count", 0);
        count_in_array = intent.getIntArrayExtra("count_in_array");
        id = intent.getStringArrayExtra("id");
        code = intent.getStringArrayExtra("code");
        cell = intent.getStringArrayExtra("cell");
        name = intent.getStringArrayExtra("name");
        quantity_in_the_package = intent.getStringArrayExtra("quantity_in_the_package");
        estimated_balance = intent.getStringArrayExtra("estimated_balance");
        actual_balance = intent.getStringArrayExtra("actual_balance");
        write_estimated_balance = intent.getStringArrayExtra("actual_balance");

        name_open = new String[count];

        beginning();
    }

    public void beginning() //вывод первого товара
    {
            total2 = "";
            cou  = 0;
            btn_back.setEnabled(false);
            btn_next.setEnabled(false);
                ab = 0;
                if(cou+1 < count)
                {
                    btn_next.setEnabled(true);
                    if (id[count_array2].matches(id[count_array2 + 1])) {
                        while (id[cou].matches(id[cou + 1])) {
                            b = Double.parseDouble(actual_balance[cou]);
                            c = Double.parseDouble(actual_balance[cou + 1]);

                            if (ab > 0) {
                                ab = ab + c;
                            } else {
                                ab = ab + b + c;
                            }

                            total2 = String.valueOf(ab);
                            cou = cou + 1;
                        }
                    }
                    else
                    {
                        ab = Double.parseDouble(actual_balance[cou]);
                        total2 = String.valueOf(ab);
                    }
                    long iPartbc = (long) ab;
                    double fPartbc = ab - iPartbc;

                    if(fPartbc <=0.0)
                    {
                        inum=(int)ab;
                        total2 = String.valueOf(inum);
                    }

                    writeCell.setText(cell[0]);
                    writeName.setText("уп=" + quantity_in_the_package[0] + " " + name[0]);
                    writeTextCode.setText(code[0]);
                    textEstimated_balance.setText(estimated_balance[0] + "/");
                    amountEstimated_balance.setText(total2);
                    writeActual_balance.setEnabled(true);
                    amountEstimated_balance.setEnabled(true);
                    writeActual_balance.setText("1");
                    textError.setText("");
                    btn_save.setEnabled(true);
                }
    }

    public void next_entry(View view) { // вывод следующий записи
            int stop = 0;
            if(count_array2 == count-1)
            {
                btn_next.setEnabled(false);
            }
            if(count_array < count-1)
            {
                if(count_next)
                {
                    count_array = count_array + 1;
                    btn_back.setEnabled(true);
                    System.out.println("cont " + count);
                    for (int j = 0; j <= count; j++) {
                        if (count_array < count - 1) {
                            while (id[count_array].matches(id[count_array - 1])) {
                                count_array = count_array + 1;
                                System.out.println("cont_array " + count_array);
                                if(count_array == count-1)
                                {
                                    btn_next.setEnabled(false);
                                }
                                break;
                            }
                        }
                    }
                    ab = 0;
                    count_array2 = count_array;
                    if (count_array2 <= count - 1) {
                        if (count_array2 + 1 != count) {
                            if (id[count_array2].matches(id[count_array2 + 1])) {
                                while (id[count_array2].matches(id[count_array2 + 1])) {
                                    b = Double.parseDouble(actual_balance[count_array2]);
                                    c = Double.parseDouble(actual_balance[count_array2 + 1]);
                                    if(ab > 0)
                                    {
                                        ab = ab + c;
                                    }
                                    else {
                                        ab = ab + b+ c;
                                    }

                                    count_array2 = count_array2 + 1;
                                    System.out.println("cont_array2 " + count_array2);

                                    if(count_array2 + 1 != count)
                                    {
                                        count_next = true;
                                    }
                                    else
                                    {
                                        if(count_array2 == count-1)
                                        {
                                            btn_next.setEnabled(false);
                                        }
                                        count_array2 = count_array2 - 1;
                                        count_next = false;
                                        break;
                                    }
                                }
                                total2 = String.valueOf(ab);

                                long iPartbc = (long) ab;
                                double fPartbc = ab - iPartbc;
                                if (fPartbc <= 0.0) {
                                    inum = (int) ab;
                                    total2 = String.valueOf(inum);
                                }
                                amountEstimated_balance.setText(total2);

                            }
                            else {
                                b = 0;
                                b = Double.parseDouble(actual_balance[count_array2]);

                                long iPart = (long) b;
                                double fPart = b - iPart;
                                total2 = String.valueOf(b);
                                if (fPart <= 0.0) {
                                    inum = (int) b;
                                    total2 = String.valueOf(inum);
                                }
                                amountEstimated_balance.setText(total2);
                            }
                        }
                        else
                        {
                            b = 0;
                            b = Double.parseDouble(actual_balance[count_array2]);

                            long iPart = (long) b;
                            double fPart = b - iPart;
                            total2 = String.valueOf(b);
                            if (fPart <= 0.0) {
                                inum = (int) b;
                                total2 = String.valueOf(inum);
                            }
                            amountEstimated_balance.setText(total2);
                            btn_next.setEnabled(false);
                        }
                    }

                }

            }


            count_array = count_array + 0;
            Animation animation = AnimationUtils.loadAnimation(this, R.anim.button_animation);
            btn_next.startAnimation(animation);

            writeCell.setText(cell[count_array]);
            writeName.setText("уп=" + quantity_in_the_package[count_array] + " " + name[count_array]);
            writeTextCode.setText(code[count_array]);
            textEstimated_balance.setText(estimated_balance[count_array] + "/");
            writeActual_balance.setEnabled(true);
            amountEstimated_balance.setEnabled(true);
            writeActual_balance.setText("1");
            textError.setText("");
            btn_save.setEnabled(true);

    }

    public void back_entry(View view) { // вывод предыдущей записи

        int stop = 0;
        if(count_array == 0)
        {
            btn_back.setEnabled(false);
        }
        if (count_array > 0){
            count_array = count_array - 1;
            count_next = true;
            btn_next.setEnabled(true);
            ab = 0;
            count_array2 = count_array;
            for (int j = 0; j <= count; j++) {
                if (count_array - 1 >= 0) {
                    if (count_array < count - 1) {
                        while (id[count_array].matches(id[count_array - 1])) {
                            count_array = count_array - 1;
                            System.out.println("cont_array " + count_array);
                            if(count_array == 0)
                            {
                                btn_back.setEnabled(false);
                            }
                            break;
                        }
                    }
                }
            }
            if (count_array2 <= count - 1) {
                if (count_array2 - 1 >= 0) {
                    if (id[count_array2].matches(id[count_array2 - 1])) {
                        while (id[count_array2].matches(id[count_array2 - 1])) {
                            b = Double.parseDouble(actual_balance[count_array2]);
                            c = Double.parseDouble(actual_balance[count_array2 - 1]);

                            if (ab > 0) {
                                ab = ab + c;
                            } else {
                                ab = ab + b + c;
                            }

                            if (count_array2 - 1 > 0) {
                                count_array2 = count_array2 - 1;
                                count_next = true;
                            }
                            else {
                                count_array2 = count_array2 + 1;
                                count_next = true;
                                break;
                            }
                        }
                        total2 = String.valueOf(ab);

                        long iPartbc = (long) ab;
                        double fPartbc = ab - iPartbc;
                        if (fPartbc <= 0.0) {
                            inum = (int) ab;
                            total2 = String.valueOf(inum);
                        }
                        amountEstimated_balance.setText(total2);
                    }
                    else {
                        b = 0;
                        b = Double.parseDouble(actual_balance[count_array2]);
                        long iPart = (long) b;
                        double fPart = b - iPart;
                        total2 = String.valueOf(b);
                        if (fPart <= 0.0) {
                            inum = (int) b;
                            total2 = String.valueOf(inum);
                        }
                        amountEstimated_balance.setText(total2);
                    }
                }
                else
                {
                    b = 0;
                    b = Double.parseDouble(actual_balance[count_array2]);

                    long iPart = (long) b;
                    double fPart = b - iPart;
                    total2 = String.valueOf(b);
                    if (fPart <= 0.0) {
                        inum = (int) b;
                        total2 = String.valueOf(inum);
                    }
                    amountEstimated_balance.setText(total2);
                    btn_back.setEnabled(false);
                }
            }
        }

        count_array = count_array + 0;
        Animation animation = AnimationUtils.loadAnimation(this, R.anim.button_animation);
        btn_back.startAnimation(animation);

        writeCell.setText(cell[count_array]);
        writeName.setText("уп=" + quantity_in_the_package[count_array] + " " + name[count_array]);
        writeTextCode.setText(code[count_array]);
        textEstimated_balance.setText(estimated_balance[count_array] + "/");
        writeActual_balance.setEnabled(true);
        amountEstimated_balance.setEnabled(true);
        writeActual_balance.setText("1");
        textError.setText("");
        btn_save.setEnabled(true);
    }

    private class CurrencyTextWatcher implements TextWatcher{ //класс для текстового сканера

        @Override
        public void beforeTextChanged(CharSequence charSequence, int i, int i1, int i2) {

        }

        @Override
        public void onTextChanged(CharSequence charSequence, int i, int i1, int i2) {
            writeCode.setOnKeyListener(new View.OnKeyListener() {
                @Override
                public boolean onKey(View view, int i, KeyEvent keyEvent) {
                    if (keyEvent.getAction() == KeyEvent.ACTION_UP && i == KeyEvent.KEYCODE_ENTER) {
                        a = writeCode.getText().toString().trim();
                        writeTextCode.setText(a); //присвоение textView текста из EditText
                        if(a.length() <= 0)
                        {
                            writeCode.requestFocus();
                            writeCode.setShowSoftInputOnFocus(keyboard);
                        }
                        int index = ArrayUtils.toArrayList(code).indexOf(a);
                        if (index >= 0) {
                            long ab = 0;
                            long ab1 = 0;
                            int cou = index;
                            int cou1 = index;
                            boolean count_scan = true;

                            if (writeTextCode != null) {
                                if (writeTextCode.getText().toString().matches(code[index])) {
                                    if (cou + 1 != count) {
                                        if (count_scan) {
                                            while (id[cou].matches(id[cou + 1])) {
                                                long c = (long) Double.parseDouble(write_estimated_balance[cou + 1]);
                                                ab += c;
                                                total2 = String.valueOf(ab);
                                                cou++;
                                                if (cou + 1 != count) {
                                                    count_scan = true;
                                                } else {
                                                    cou = cou - 1;
                                                    count_scan = false;
                                                    break;
                                                }
                                            }
                                        }
                                    }
                                    if (cou1 - 1 >= 0) {
                                        while (id[cou1].matches(id[cou1 - 1])) {
                                            long c = (long) Double.parseDouble(write_estimated_balance[cou1 - 1]);
                                            ab1 += c;
                                            total2 = String.valueOf(ab1);
                                            if(cou1 - 1 > 0)
                                            {
                                                cou1--;
                                            }
                                            else
                                            {
                                                cou1 = cou1 + 0;
                                                break;
                                            }
                                        }
                                    }

                                    long b = (long) Double.parseDouble(write_estimated_balance[index]);
                                    ab += ab1 + b;
                                    total2 = String.valueOf(ab);
                                    long iPartbc = (long) ab;
                                    double fPartbc = ab - iPartbc;

                                    if(fPartbc <=0.0)
                                    {
                                        inum=(int)ab;
                                        total2 = String.valueOf(inum);
                                    }
                                    count_array = 0 + index;
                                    if (count_array - 1 >= 0) {
                                            while (id[count_array].matches(id[count_array - 1])) {
                                                count_array = count_array - 1;
                                            }
                                    }
                                    writeCell.setText(cell[index]);
                                    writeName.setText("уп=" + quantity_in_the_package[index] + " " + name[index]);
                                    writeTextCode.setText(code[index]);
                                    textEstimated_balance.setText(estimated_balance[index] + "/");
                                    amountEstimated_balance.setText(total2);
                                    writeActual_balance_focus();
                                    textError.setText("");
                                    btn_save.setEnabled(true);
                                }
                            }
                        }
                        else
                        {
                            writeCell.setText("");
                            writeName.setText("");
                            textEstimated_balance.setText("");
                            amountEstimated_balance.setText("");
                            writeActual_balance.setText("");
                            writeActual_balance.setEnabled(false);
                            textError.setText("Такого скан-кода нет");
                            btn_save.setEnabled(false);
                            writeCode.selectAll();
                            count_array = 0;
                        }

                        return true;
                    }
                    return false;
                }
            });

        }

        @Override
        public void afterTextChanged(Editable editable) {

        }
    }


    private class CurrencyTextWatcher_balance implements TextWatcher{ // изменение поля найденого количества
        @Override
        public void beforeTextChanged(CharSequence charSequence, int i, int i1, int i2) {

        }
        @Override
        public void onTextChanged(CharSequence charSequence, int i, int i1, int i2) {

            writeActual_balance.setOnKeyListener(new View.OnKeyListener() {
                @RequiresApi(api = Build.VERSION_CODES.N)
                @Override
                public boolean onKey(View view, int i, KeyEvent keyEvent) {

                    if(keyEvent.getAction() == KeyEvent.ACTION_UP && i == KeyEvent.KEYCODE_SPACE)
                    {
                        writeActual_balance.setText("-");
                        writeActual_balance.setSelection(writeActual_balance.getText().length());
                        writeActual_balance.requestFocus();
                    }

                    if (keyEvent.getAction() == KeyEvent.ACTION_UP && i == KeyEvent.KEYCODE_ENTER) {
                        String value = writeActual_balance.getText().toString().trim();

                        if (writeActual_balance.getText().toString().matches("-"))
                        { //если введен только минус в количестве
                            AlertDialog.Builder builder = new AlertDialog.Builder(MainActivitySecond.this);
                            builder.setMessage("Вы не ввели число!")
                                    .setCancelable(false)
                                    .setPositiveButton("OK", new DialogInterface.OnClickListener() {
                                        public void onClick(DialogInterface dialog, int id) {
                                            writeActual_balance.setSelection(writeActual_balance.getText().length());
                                            writeActual_balance.requestFocus();
                                        }
                                    });
                            AlertDialog alert = builder.create();
                            alert.show();
                        }
                        else if (value.length() >= 8)
                        { //если длина вводимого больше или равно 8
                            AlertDialog.Builder builder = new AlertDialog.Builder(MainActivitySecond.this);
                            builder.setMessage("Неверно указано количество.")
                                    .setCancelable(false)
                                    .setPositiveButton("OK", new DialogInterface.OnClickListener() {
                                        public void onClick(DialogInterface dialog, int id) {
                                            writeActual_balance.setText("1"); // возвращение единицы
                                            writeCode.requestFocus(); // фокус на воде кода
                                            writeCode.setShowSoftInputOnFocus(keyboard);
                                            writeActual_balance.setShowSoftInputOnFocus(keyboard);
                                        }
                                    });
                            AlertDialog alert = builder.create();
                            alert.show();
                        }
                        else {
                            if (writeActual_balance.getText().toString().length() > 0)
                            { // если введенное кол-во не пустое
                                saveText(view); // сохранение файла
                                writeActual_balance.setEnabled(true);
                                amountEstimated_balance.setEnabled(true);
                                writeActual_balance.setText("1");
                                writeCode.requestFocus();
                                writeCode.setShowSoftInputOnFocus(keyboard);
                                writeActual_balance.setShowSoftInputOnFocus(keyboard);
                                btn_save.setEnabled(true);
                            }
                            else
                            {
                                writeActual_balance.setError("Пустое поле!");
                            }

                            int index = ArrayUtils.toArrayList(code).indexOf(writeTextCode.getText());
                            ab = 0;
                            ab1 = 0;
                            cou = 0;
                            cou1 = 0;
                            count_scan = true;

                            if (writeTextCode != null) {
                                if (writeTextCode.getText().toString().matches(code[index])) {
                                    cou = index;
                                    if (cou + 1 != count) {
                                        if (count_scan) {
                                            while (id[cou].matches(id[cou + 1])) {
                                                c = Double.parseDouble(write_estimated_balance[cou + 1]);

                                                ab = ab + c;
                                                total2 = String.valueOf(ab);
                                                cou++;
                                                if (cou + 1 != count)
                                                {
                                                    count_scan = true;
                                                }
                                                else {
                                                    cou = cou - 1;
                                                    count_scan = false;
                                                    break;
                                                }
                                            }
                                        }
                                    }
                                    cou1 = index;
                                    if (cou1 - 1 >= 0) {
                                        while (id[cou1].matches(id[cou1 - 1])) {
                                            c = Double.parseDouble(write_estimated_balance[cou1 - 1]);

                                            ab1 = ab1 + c;
                                            total2 = String.valueOf(ab1);
                                            if(cou1 - 1 > 0)
                                            {
                                                cou1--;
                                            }
                                            else
                                            {
                                                cou1 = cou1 + 0;
                                                break;
                                            }
                                        }
                                    }
                                    b = Double.parseDouble(write_estimated_balance[index]);
                                    ab = ab + ab1 + b;
                                    total2 = String.valueOf(ab);
                                    long iPartbc = (long) ab;
                                    double fPartbc = ab - iPartbc;

                                    if(fPartbc <=0.0)
                                    {
                                        inum=(int)ab;
                                        total2 = String.valueOf(inum);
                                    }

                                    writeCell.setText(cell[index]);
                                    writeName.setText("уп=" + quantity_in_the_package[index] + " " + name[index]);
                                    writeTextCode.setText(code[index]);
                                    textEstimated_balance.setText(estimated_balance[index] + "/");
                                    amountEstimated_balance.setText(total2);
                                    textError.setText("");
                                    btn_save.setEnabled(true);
                                }
                                else {
                                    writeCell.setText("");
                                    writeName.setText("");
                                    writeActual_balance.setText("");
                                    textError.setText("Такого скан-кода нет");
                                    btn_save.setEnabled(false);
                                }
                            }

                            writeActual_balance.setEnabled(true);
                            amountEstimated_balance.setEnabled(true);
                            writeActual_balance.setText("1");
                            writeCode.requestFocus();
                            writeCode.setShowSoftInputOnFocus(keyboard);
                            btn_save.setEnabled(true);
                            return true;
                        }
                    }
                    return false;
                }
            });
        }
        @Override
        public void afterTextChanged(Editable editable) {

        }

    }

    public void scan(View view) {
        scanCode();
    }
    private void scanCode() // скнирование камерой
    {
        ScanOptions options = new ScanOptions();
        options.setPrompt("Увеличение громкости для включения вспышки");
        options.setBeepEnabled(true);
        options.setOrientationLocked(true);
        options.setCaptureActivity(CaptureAct.class);
        barLauncher.launch(options);
    }


    @SuppressLint("SetTextI18n") //сканер камера
    ActivityResultLauncher<ScanOptions> barLauncher = registerForActivityResult(new ScanContract(), result ->
    {
        int index = ArrayUtils.toArrayList(code).indexOf(result.getContents());
        if (index >= 0) {

            int cou = index;
            int cou1 = index;
            boolean count_scan = true;
                    ab = 0;
                    ab1 = 0;
                    cou = 0;
                    cou1 = 0;
                    count_scan = true;

                    if (result.getContents() != null) {
                        if (result.getContents().matches(code[index])) {
                            cou = index;
                            if(cou + 1 != count) {
                                if (count_scan) {
                                    while (id[cou].matches(id[cou + 1])) {
                                        c = Double.parseDouble(write_estimated_balance[cou + 1]);

                                        ab = ab + c;

                                        total2 = String.valueOf(ab);

                                        cou++;
                                        if (cou + 1 != count)
                                        {
                                            count_scan = true;
                                        }
                                        else
                                        {
                                            cou = cou - 1;
                                            count_scan = false;
                                            break;
                                        }
                                    }
                                }
                            }
                            cou1 = index;
                            if (cou1 - 1 >= 0) {
                                while (id[cou1].matches(id[cou1 - 1])) {

                                    c = Double.parseDouble(write_estimated_balance[cou1 - 1]);

                                    ab1 = ab1 + c;

                                    total2 = String.valueOf(ab1);
                                    if(cou1 - 1 > 0)
                                    {
                                        cou1--;
                                    }
                                    else
                                    {
                                        cou1 = cou1 + 0;
                                        break;
                                    }

                                }
                            }

                            b = Double.parseDouble(write_estimated_balance[index]);
                            ab = ab + ab1 + b;
                            total2 = String.valueOf(ab);
                            long iPartbc = (long) ab;
                            double fPartbc = ab - iPartbc;

                            if(fPartbc <=0.0)
                            {
                                inum=(int)ab;
                                total2 = String.valueOf(inum);
                            }

                            count_array = 0 + index;

                            if (count_array - 1 >= 0) {
                                if (count_array < count - 1) {
                                    while (id[count_array].matches(id[count_array - 1])) {
                                        count_array = count_array - 1;

                                    }
                                }
                            }

                            writeCell.setText(cell[index]);
                            writeName.setText("уп=" + quantity_in_the_package[index] + " " + name[index]);
                            writeTextCode.setText(code[index]);
                            textEstimated_balance.setText(estimated_balance[index] + "/");
                            amountEstimated_balance.setText(total2);
                            writeActual_balance_focus();
                            textError.setText("");
                            btn_save.setEnabled(true);

                        }
                    }
                }
        else {
            writeCell.setText("");
            writeName.setText("");
            writeTextCode.setText(result.getContents());
            textEstimated_balance.setText("");
            amountEstimated_balance.setText("");
            writeActual_balance.setText("");
            writeActual_balance.setEnabled(false);
            textError.setText("Такого скан-кода нет");
            btn_save.setEnabled(false);
            count_array = 0;
        }
    });

    @RequiresApi(api = Build.VERSION_CODES.N)

    public void saveText(View view){ //сохранение файла

        String path = Environment.getExternalStoragePublicDirectory("Android/data/com.example.testapp/files/invoice/").getAbsolutePath(); //папка с азгрузками

        String path_tmp = Environment.getExternalStoragePublicDirectory("Android/data/com.example.testapp/files/invoice_tmp/").getAbsolutePath(); //папка с азгрузками

        File directory_tmp = new File(path_tmp);
        File[] files_tmp = directory_tmp.listFiles();
        Arrays.sort(files_tmp, Comparator.comparingLong(File::lastModified));

        SharedPreferences preferencesFile = getSharedPreferences("MyPreferencesFile", MODE_PRIVATE);

        String path2 = path + "/" + preferencesFile.getString("lastFileSend", "") + ".txt";
        file2 = new File(path2);
        fin = null;

        for (int i = 0; i < files_tmp.length; i++) { //перебор файла
            String path_tmp2 = path_tmp + "/" + files_tmp[i].getName();
            file_tmp = new File(path_tmp2);
            fin = null;
        }
            try {
                fin = new FileInputStream(file2);
                byte[] bytes = new byte[fin.available()];
                fin.read(bytes);
                String text = new String(bytes);

                int count = text.split("\r\n|\r|\n").length; //подсчет строк
                String[] main = new String[count];
                String[] main_new = new String[count];
                String[] main_all = new String[count];
                List<String> values = new ArrayList<>();
                StringBuilder sb = new StringBuilder(128);
                for (int j = 0; j < count; j++) {

                    if (code[j].matches(writeTextCode.getText().toString())) { //если код на экране и код в файле совпадают
                        //складывание найденого остатка с набранным

                        if (writeActual_balance.getText().toString().length() > 0) {
                            b = Double.parseDouble(writeActual_balance.getText().toString()) * Double.parseDouble(quantity_in_the_package[j]);
                            c = Double.parseDouble(actual_balance[j]);

                            long iPart = (long) b;
                            double fPart = b - iPart;

                            long iPartc = (long) c;
                            double fPartc = c - iPartc;

                            ab = b + c;

                            NumberFormat nf = new DecimalFormat("#.###");
                            ab = Double.parseDouble(nf.format(ab).replace(",", "."));
                                if (fPartc <= 0.0) {
                                    if (Math.abs(fPart) <= 0.0) {
                                        inum = (int) ab;
                                        actual_balance[j] = Integer.toString(inum);
                                        System.out.println("inum1 " + inum);
                                    } else {
                                        actual_balance[j] = Double.toString(ab);
                                        System.out.println("ab1 " + ab);
                                    }
                                } else {
                                    if (Math.abs(fPart) <= 0.0) {
                                        inum = (int) ab;
                                        actual_balance[j] = Double.toString(ab);
                                        System.out.println("inum2 " + inum);
                                    } else {
                                        actual_balance[j] = Double.toString(ab);
                                        System.out.println("ab2 " + ab);
                                    }
                                }
                                main_new[j] = id[j] + ";" + code[j] + ";" + cell[j] + ";" + name[j] + ";" + quantity_in_the_package[j] +
                                        ";" + estimated_balance[j] + ";" + actual_balance[j].trim().replaceAll("\\s+", "")
                                        .replaceAll("\\n+", "").trim() + "\n";
                            }

                        else
                        {
                            actual_balance[j] = actual_balance[j];
                            main_new[j] = id[j] + ";" + code[j] + ";" + cell[j] + ";" + name[j] + ";" + quantity_in_the_package[j] +
                                    ";" + estimated_balance[j] + ";" + actual_balance[j].trim().replaceAll("\\s+", "")
                                    .replaceAll("\\n+", "").trim() + "\n";
                        }

                    }
                    else {
                            main[j] = id[j] + ";" + code[j] + ";" + cell[j] + ";" + name[j] + ";" + quantity_in_the_package[j] +
                                    ";" + estimated_balance[j] + ";" + actual_balance[j].trim().replaceAll("\\s+", "")
                                    .replaceAll("\\n+", "").trim() + "\n";
                    }
                    if (main_new[j] != null && main_new[j].length() > 0)
                    {
                        main_all[j] = main_new[j];
                    }
                    else if (main[j] != null && main[j].length() > 0)
                    {
                        main_all[j] = main[j];
                    }
                    values.add(main_all[j]);
                }
                for (String value : values) {
                    if (sb.length() > 0) {
                        sb.append("@");
                    }
                    sb.append(value);
                }
                sb.insert(0, "[");
                sb.append("]");

                FileOutputStream fos = null;
                FileOutputStream fos_tmp = null;
                try {
                    String txt = (sb.toString().replace("[", "").replace("]", "")
                            .replace("@", "").trim());

                    fos = new FileOutputStream(file2);
                    fos.write(txt.getBytes()); //сохранение измененного файла
                    fos_tmp = new FileOutputStream(file_tmp);
                    fos_tmp.write(txt.getBytes()); //сохранение измененного файла
                    openTextFile.setText("");
                    Toast.makeText(this, "Файл сохранен", Toast.LENGTH_SHORT).show();
                    //openText(view); //показ обновленного файла

                    int index = ArrayUtils.toArrayList(code).indexOf(writeTextCode.getText());

                    ab = 0;
                    ab1 = 0;
                    cou = 0;
                    cou1 = 0;
                    count_scan = true;

                    if (writeTextCode != null) {
                        if (writeTextCode.getText().toString().matches(code[index])) {
                            cou = index;
                            if (cou + 1 != count) {
                                if (count_scan) {
                                    while (id[cou].matches(id[cou + 1])) {
                                        c = Double.parseDouble(write_estimated_balance[cou + 1]);

                                        ab = ab + c;
                                        total2 = String.valueOf(ab);
                                        cou++;
                                        if (cou + 1 != count)
                                        {
                                            count_scan = true;
                                        }
                                        else {
                                            cou = cou - 1;
                                            count_scan = false;
                                            break;
                                        }
                                    }
                                }
                            }
                            cou1 = index;
                            if (cou1 - 1 >= 0) {
                                while (id[cou1].matches(id[cou1 - 1])) {
                                    c = Double.parseDouble(write_estimated_balance[cou1 - 1]);

                                    ab1 = ab1 + c;
                                    total2 = String.valueOf(ab1);
                                    if(cou1 - 1 > 0)
                                    {
                                        cou1--;
                                    }
                                    else
                                    {
                                        cou1 = cou1 + 0;
                                        break;
                                    }
                                }
                            }
                            b = Double.parseDouble(write_estimated_balance[index]);
                            ab = ab + ab1 + b;
                            total2 = String.valueOf(ab);
                            long iPartbc = (long) ab;
                            double fPartbc = ab - iPartbc;

                            if(fPartbc <=0.0)
                            {
                                inum=(int)ab;
                                total2 = String.valueOf(inum);
                            }

                            writeCell.setText(cell[index]);
                            writeName.setText("уп=" + quantity_in_the_package[index] + " " + name[index]);
                            writeTextCode.setText(code[index]);
                            textEstimated_balance.setText(estimated_balance[index] + "/");
                            amountEstimated_balance.setText(total2);
                            textError.setText("");
                            btn_save.setEnabled(true);
                        }
                        else {
                            writeCell.setText("");
                            writeName.setText("");
                            writeActual_balance.setText("");
                            textError.setText("Такого скан-кода нет");
                        }
                    }

                    writeActual_balance.setEnabled(true);
                    amountEstimated_balance.setEnabled(true);
                    writeActual_balance.setText("1");
                    writeCode.requestFocus();
                    writeCode.setShowSoftInputOnFocus(keyboard);

                } catch (IOException ex) {
                    Toast.makeText(this, ex.getMessage(), Toast.LENGTH_SHORT).show();
                } finally {
                    try {
                        if (fos != null)
                            fos.close();
                    } catch (IOException ex) {
                        Toast.makeText(this, ex.getMessage(), Toast.LENGTH_SHORT).show();
                    }
                }
            } catch (IOException ex) {
                Toast.makeText(this, ex.getMessage(), Toast.LENGTH_SHORT).show();
            } finally {
                try {
                    if (fin != null)
                        fin.close();
                } catch (IOException ex) {
                    Toast.makeText(this, ex.getMessage(), Toast.LENGTH_SHORT).show();
                }
            }
        }

    // открытие файла
    @SuppressLint("SetTextI18n")
    public void openText(View view) {
        String path = Environment.getExternalStoragePublicDirectory("Android/data/com.example.testapp/files/invoice/").getAbsolutePath(); //папка с азгрузками
        SharedPreferences preferencesFile = getSharedPreferences("MyPreferencesFile", MODE_PRIVATE);
        String path2 = path + "/" + preferencesFile.getString("lastFileSend", "") + ".txt";
        file2 = new File(path2);
        fin = null;

        openTextFile = findViewById(R.id.text);
        try {
            fin = new FileInputStream(file2);

            byte[] bytes = new byte[fin.available()];
            fin.read(bytes);
            String text = new String(bytes);

            count = text.split("\n").length; //подсчет колва строк

            count_in_array = new int[count];

            ab = 0;
            ab1 = 0;
            ab2 = 0;
            cou = 0;
            cou1 = 0;
            count_scan = true;
            for (int j = 0; j < count; j++) {
                if (cou <= count - 1) {
                    if (cou + 1 != count) {
                        if (count_scan) {
                            if (id[cou].matches(id[cou + 1])) {
                                ab = 0;
                                while (id[cou].matches(id[cou + 1])) {
                                    b = Double.parseDouble(write_estimated_balance[cou]);
                                    c = Double.parseDouble(write_estimated_balance[cou + 1]);

                                    if (ab > 0) {
                                        ab = ab + c;
                                    } else {
                                        ab = ab + b + c;
                                    }

                                    total2 = String.valueOf(ab);

                                    long iPartbc = (long) ab;
                                    double fPartbc = ab - iPartbc;

                                    if (fPartbc <= 0.0) {
                                        inum = (int) ab;
                                        total2 = String.valueOf(inum);
                                    }

                                    name_open[j] = " Название: " + name[cou] + "\n" +
                                            " Расчетный остаток: " + estimated_balance[cou] + "\n" + " Фактический остаток: " + total2 + "\n\n";

                                    cou = cou + 1;

                                    if (cou + 1 != count) {
                                        count_scan = true;
                                    } else {
                                        cou = cou - 1;
                                        count_scan = false;
                                        break;
                                    }
                                }
                            }

                            else if (id[cou].matches(id[cou + 1]) == false) {
                                if (cou == 0) {
                                    ab = 0;
                                    System.out.println("A");
                                    ab = Double.parseDouble(write_estimated_balance[cou]);
                                    long iPart = (long) ab;
                                    double fPart = ab - iPart;
                                    total2 = String.valueOf(ab);
                                    if (fPart <= 0.0) {
                                        inum = (int) ab;
                                        total2 = String.valueOf(inum);
                                    }
                                    name_open[j] = " Название: " + name[cou] + "\n" +
                                                " Расчетный остаток: " + estimated_balance[cou] + "\n" + " Фактический остаток: " + total2 + "\n\n";

                                    cou = cou + 1;

                                    if (cou + 1 != count) {
                                        count_scan = true;
                                    } else {
                                        cou = cou - 1;
                                        count_scan = false;
                                        break;
                                    }
                                }
                                else
                                {
                                    cou = cou + 1;
                                    if (cou + 1 != count)
                                    {
                                    ab2 = 0;
                                    ab = 0;
                                    if (id[cou].matches(id[cou + 1])) {
                                        while (id[cou].matches(id[cou + 1])) {
                                            b = Double.parseDouble(write_estimated_balance[cou]);
                                            c = Double.parseDouble(write_estimated_balance[cou + 1]);

                                            if (ab > 0) {
                                                ab = ab + c;
                                            } else {
                                                ab = ab + b + c;
                                            }

                                            total2 = String.valueOf(ab);

                                            long iPartbc = (long) ab;
                                            double fPartbc = ab - iPartbc;

                                            if (fPartbc <= 0.0) {
                                                inum = (int) ab;
                                                total2 = String.valueOf(inum);
                                            }

                                            name_open[j] = " Название: " + name[cou] + "\n" +
                                                    " Расчетный остаток: " + estimated_balance[cou] + "\n" + " Фактический остаток: " + total2 + "\n\n";

                                            cou = cou + 1;

                                            if (cou + 1 != count) {
                                                count_scan = true;
                                            } else {
                                                cou = cou - 1;
                                                count_scan = false;
                                                break;
                                            }
                                        }
                                    } else {
                                        b = Double.parseDouble(write_estimated_balance[cou]);

                                        ab2 = ab2 + b;

                                        total2 = String.valueOf(ab2);

                                        long iPartbc = (long) ab2;
                                        double fPartbc = ab2 - iPartbc;

                                        if (fPartbc <= 0.0) {
                                            inum = (int) ab2;
                                            total2 = String.valueOf(inum);
                                        }

                                        name_open[j] = " Название: " + name[cou] + "\n" +
                                                " Расчетный остаток: " + estimated_balance[cou] + "\n" + " Фактический остаток: " + total2 + "\n\n";


                                        if (cou + 1 != count) {
                                            count_scan = true;
                                        } else {
                                            cou = cou - 1;
                                            count_scan = false;
                                            break;
                                        }
                                    }
                                } else {
                                    ab2 = 0;
                                    b = Double.parseDouble(write_estimated_balance[cou]);

                                    ab2 = ab2 + b;

                                    total2 = String.valueOf(ab2);

                                    long iPartbc = (long) ab2;
                                    double fPartbc = ab2 - iPartbc;

                                    if (fPartbc <= 0.0) {
                                        inum = (int) ab2;
                                        total2 = String.valueOf(inum);
                                    }

                                    name_open[j] = " Название: " + name[cou] + "\n" +
                                            " Расчетный остаток: " + estimated_balance[cou] + "\n" + " Фактический остаток: " + total2 + "\n\n";
                                    cou = cou + 1;
                                }
                            }
                            }
                        }
                    }
                }
            }
                af=0;
                for(final String s : name_open) {
                    if(s != null){
                        ++af;
                    }
                }
                af2 = 0;
            StringBuilder sb = new StringBuilder();
            for (final String s : name_open) {
                if (s != null) {
                    sb.append(s);
                    sb.append("@"); // Используем символ ";" в качестве разделителя
                    ++af2;
                }
            }

            openTextFile.setText(" " + sb.toString().replace("@", "")  //вывод текста файла
                    .replace("[", "").replace("]", "")
                    .trim());

            } catch (IOException ex) {

                Toast.makeText(this, ex.getMessage(), Toast.LENGTH_SHORT).show();
            } finally {
                try {
                    if (fin != null)
                        fin.close();
                } catch (IOException ex) {
                    Toast.makeText(this, ex.getMessage(), Toast.LENGTH_SHORT).show();
                }
            }
        }

    private void writeActual_balance_focus() // фокус на поле найденое колв-во
    {
        writeActual_balance.setEnabled(true);
        amountEstimated_balance.setEnabled(true);
        writeActual_balance.setText("1");
        writeActual_balance.setSelection(writeActual_balance.getText().length());
        writeActual_balance.requestFocus();
        writeActual_balance.selectAll();
        writeCode.setShowSoftInputOnFocus(keyboard);
        writeActual_balance.setShowSoftInputOnFocus(keyboard);
    }
}