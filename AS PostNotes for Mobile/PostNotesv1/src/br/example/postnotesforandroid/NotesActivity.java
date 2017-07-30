package br.example.postnotesforandroid;

import java.util.ArrayList;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import android.annotation.SuppressLint;
import android.content.Intent;
import android.os.Bundle;
import android.support.v7.app.ActionBarActivity;
import android.util.Log;
import android.view.View;
import android.widget.ListView;

public class NotesActivity extends ActionBarActivity {
	
	public static String user_login = "";
	public static String dados_json = "";
	
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_notes);
		
		Intent intent = getIntent();
		if(intent != null) {
			Bundle params = intent.getExtras();
			if(params != null) {
				user_login = params.getString("login");
			} else {
				finish();
			}
		} else {
			finish();
		}
	}
	
	@Override
	protected void onResume() {
		super.onResume();
		getNotesHttpPost("get-notes", user_login,"");
		ArrayList<Nota> notas = new ArrayList<Nota>();
		ListView lv = (ListView) findViewById(R.id.lv);
		
		notas = pegarNotas(dados_json);
		
		lv.setAdapter(new NotaAdapter(this, notas));
	}
	
	public void refreshNotes(View view) {
		
		/* LISTA */
		
		getNotesHttpPost("get-notes", user_login,"");
		
		ArrayList<Nota> notas = new ArrayList<Nota>();
		ListView lv = (ListView) findViewById(R.id.lv);
		
		notas = pegarNotas(dados_json);
		
		
		lv.setAdapter(new NotaAdapter(this, notas));
		
		/* FIM LISTA */

	}
	
	public void logout (View view) {
		//returnTrue();
		Intent intent = new Intent(NotesActivity.this ,LoginActivity.class);
    	
    	Bundle params = new Bundle();
		params.putString("login", user_login);
		intent.putExtras(params);
    	
		startActivity(intent);
		finish();
	}
	public void returnTrue() {
		Intent intent = new Intent();
		intent.putExtra("msg", "Sucesso");
		setResult(1, intent);
		finish();
	}
	
	/* Codigo JSON */
	public ArrayList<Nota> pegarNotas(final String data) {
		ArrayList<Nota> notas_novas = new ArrayList<Nota>();
		try {
			JSONObject jo = new JSONObject(data);
			JSONArray ja;
			ja = jo.getJSONArray("bindings");
			for(int i = 0, tam = ja.length(); i < tam; i++) {
				notas_novas.add(
						new Nota(
								ja.getJSONObject(i).getString("id_notes"),
								ja.getJSONObject(i).getString("annotation"),
								ja.getJSONObject(i).getString("creator"),
								ja.getJSONObject(i).getString("alarm"),
								ja.getJSONObject(i).getString("date")
								)
						);
			}
		}
		catch(JSONException e){ e.printStackTrace(); }
		return (notas_novas);
	}
	/* FIM */	
	
	@SuppressLint("NewApi")
	private void getNotesHttpPost(final String method, final String user, final String senha) {
		Thread t = new Thread(new Runnable(){
			public void run(){
				String answer = HttpConnection.sendHttpPost("http://www.postnotes.com.br/JSON/processo.php", method, user, senha);
				Log.i("Script", "ANSWER: "+answer);
				dados_json = answer;
			}
		});
		t.start();
		try {
			t.join();
		} catch (InterruptedException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}
}
