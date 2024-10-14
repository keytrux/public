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

public class LoginActivity extends AppCompatActivity {

    Button btnSign;
    EditText etPhone, etPassword;
    private String USER_KEY = "User"; //название таблицы

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);

        btnSign = (Button) findViewById(R.id.loginbtn);
        etPhone = (EditText) findViewById(R.id.phone);
        etPassword = (EditText) findViewById(R.id.password);

    }

    public Boolean validatePhone()
    {
        String val = etPhone.getText().toString();
        if (val.isEmpty())
        {
            etPhone.setError("Username");
            return false;
        }
        else
        {
            etPhone.setError(null);
            return true;
        }
    }
    public Boolean validatePassword()
    {
        String val = etPassword.getText().toString();
        if (val.isEmpty())
        {
            etPassword.setError("Username");
            return false;
        }
        else
        {
            etPassword.setError(null);
            return true;
        }
    }


    public void checkUser()
    {
        String number = etPhone.getText().toString().trim();
        String password = etPassword.getText().toString().trim();


        DatabaseReference reference = FirebaseDatabase.getInstance().getReference("User");
        Query checkUserDataBase = reference.orderByChild("number").equalTo(number);

        checkUserDataBase.addListenerForSingleValueEvent(new ValueEventListener() {
            @Override
            public void onDataChange(@NonNull DataSnapshot snapshot) {
                if (snapshot.exists())
                {
                    etPhone.setError(null);
                    String passwordFromDB = snapshot.child(number).child("password").getValue(String.class);

                    if(passwordFromDB.equals(password))
                    {
                        etPhone.setError(null);

                        String nameFromDB = snapshot.child(number).child("name").getValue(String.class);
                        String numberFromDB = snapshot.child(number).child("number").getValue(String.class);

                        Intent intent = new Intent(LoginActivity.this, MainActivity.class);

                        intent.putExtra("name", nameFromDB);
                        intent.putExtra("number", numberFromDB);

                        startActivity(intent);
                    }
                    else
                    {
                        etPassword.setError("Invalid");
                        etPassword.requestFocus();
                    }
                }
                else
                {
                    etPhone.setError("User does not exist");
                    etPhone.requestFocus();
                }
            }

            @Override
            public void onCancelled(@NonNull DatabaseError error) {

            }
        });

    }

    public void signup(View view) {
        if (!validatePhone() | !validatePassword())
        {

        }
        else
        {
            checkUser();
        }
    }

    public void signin(View view) {
        Intent intent = new Intent(LoginActivity.this, SignIn.class);
        startActivity(intent);
    }
}