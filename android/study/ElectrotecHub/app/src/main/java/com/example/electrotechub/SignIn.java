package com.example.electrotechub;

import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;

import com.google.firebase.database.DataSnapshot;
import com.google.firebase.database.DatabaseError;
import com.google.firebase.database.DatabaseReference;
import com.google.firebase.database.FirebaseDatabase;
import com.google.firebase.database.Query;
import com.google.firebase.database.ValueEventListener;


public class SignIn extends AppCompatActivity{


    public String numberFromDB ;
    Button btnSign;
    EditText etPhone, etPassword, etName;
    private DatabaseReference mDataBase;
    private String USER_KEY = "User"; //название таблицы
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.acriviry_signin);

        btnSign = (Button) findViewById(R.id.signinbtn);
        etName = (EditText) findViewById(R.id.name);
        etPhone = (EditText) findViewById(R.id.phone);
        etPassword = (EditText) findViewById(R.id.password);

        mDataBase = FirebaseDatabase.getInstance().getReference(USER_KEY); //создается таблица

    }


    public void blablabla(View view) {
        String name = etName.getText().toString();
        String number = etPhone.getText().toString();
        String password = etPassword.getText().toString();
        User newuser = new User(number, password, name);


        EditText vname = (EditText) findViewById(R.id.name);
        String sUsername = vname.getText().toString();

        EditText vnumber = (EditText) findViewById(R.id.phone);
        String sUsernumber = vnumber.getText().toString();

        EditText vpassword = (EditText) findViewById(R.id.password);
        String pas = vpassword.getText().toString();

        DatabaseReference reference = FirebaseDatabase.getInstance().getReference("User");
        Query checkUserDataBase = reference.orderByChild("number").equalTo(number);


        checkUserDataBase.addListenerForSingleValueEvent(new ValueEventListener() {
            @Override
            public void onDataChange(@NonNull DataSnapshot snapshot) {
                if (snapshot.exists()) {
                    etPhone.setError(null);
                    numberFromDB = snapshot.child(number).child("number").getValue(String.class);
                    if (sUsernumber.equals(numberFromDB)) {
                        etPhone.setError("Номер занят");
                        return;
                    }


                }
            }

            @Override
            public void onCancelled(@NonNull DatabaseError error) {
            }
        });
        
        if (sUsername.matches("")) {
            etName.setError("Вы не ввели имя");
            return;
        }
        else if (sUsernumber.matches("")) {
            etPhone.setError("Вы не ввели номер");
            return;
        }
        else if (sUsernumber.equals(numberFromDB))
        {
            etPhone.setError("Номер занят");
            return;
        }
        else if (pas.matches("")) {
            etPassword.setError("Вы не ввели пароль");
            return;
        }
        else
        {
            mDataBase.child(number).setValue(newuser);
            Intent intent = new Intent(SignIn.this, LoginActivity.class);
            startActivity(intent);
        }


    }

    public void have(View view) {
        Intent intent = new Intent(SignIn.this, LoginActivity.class);
        startActivity(intent);

    }
}


