package br.example.postnotesforandroid;

import java.sql.Date;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import android.annotation.SuppressLint;
import android.app.Notification;
import android.app.NotificationManager;
import android.app.PendingIntent;
import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.graphics.BitmapFactory;
import android.media.Ringtone;
import android.media.RingtoneManager;
import android.net.Uri;
import android.os.Bundle;
import android.support.v4.app.NotificationCompat;
import android.util.Log;
import android.widget.Toast;

public class BroadcastNotifica extends BroadcastReceiver {
	public static String login = "";
	public static String msg = "";
	public static String tempo = "";
	@Override
	public void onReceive(Context context, Intent intent) {
		// TODO Auto-generated method stub
		Log.i("Script","Entrou no Broadcast");
		
		pegarTempo();
		
		SimpleDateFormat formatter = new SimpleDateFormat("dd-MM-yyyy hh:mm:ss aa");
		
		Date tempo_atual = null;
		try {
			tempo_atual = (Date)formatter.parse(tempo);
		} catch (ParseException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		ArrayList<Nota> notas = pegarNotas(msg);
		for(int i = 0, tam = notas.size() ; i < tam; i++) {
			Nota nota_temp = notas.get(i);
			String tempo_alarme = nota_temp.getAlarm();
			
			Date alarme = null;
			try {
				alarme = (Date)formatter.parse(tempo_alarme);
			} catch (ParseException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
			
			if (tempo_atual.after(alarme)) {
				gerarNotificacao(context, new Intent(context, ClockerActivity.class), "", "", "");
			}
		}
	}
	
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
	
	@SuppressLint("NewApi")
	private void pegarTempo() {
		Thread t = new Thread( new Runnable(){
			public void run(){
				msg = HttpConnection.sendHttpPost("http://www.postnotes.com.br/JSON/processo.php", "get-server-time", "", "");
				Log.i("Script", "ANSWER: "+msg);
				tempo = msg;
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
	
	@SuppressLint({ "NewApi", "ShowToast" })
	private void logarHttp(final String method, final String user, final String senha) {
		Thread t = new Thread( new Runnable(){
			public void run(){
				msg = HttpConnection.sendHttpPost("http://www.postnotes.com.br/JSON/processo.php", method, user, senha);
				Log.i("Script", "ANSWER: "+msg);
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
	
	public void gerarNotificacao (Context context, Intent intent, CharSequence ticker, CharSequence titulo, CharSequence descricao) {
		NotificationManager nm = (NotificationManager) context.getSystemService(context.NOTIFICATION_SERVICE);
		PendingIntent p = PendingIntent.getActivity(context, 0, intent, 0);
		
		
		NotificationCompat.Builder builder = new NotificationCompat.Builder(context);
		builder.setTicker(ticker);
		builder.setContentTitle(titulo);
		builder.setContentText(descricao);
		builder.setSmallIcon(R.drawable.ic_launcher);
		builder.setLargeIcon(BitmapFactory.decodeResource(context.getResources(), R.drawable.ic_launcher));
		builder.setContentIntent(p);

		
		Notification n = builder.build();
		n.vibrate = new long[]{150, 300, 150, 600};
		n.flags = Notification.FLAG_AUTO_CANCEL;
		nm.notify(R.drawable.ic_launcher, n);
		
		try {
			Uri som = RingtoneManager.getDefaultUri(RingtoneManager.TYPE_NOTIFICATION);
			Ringtone toque = RingtoneManager.getRingtone(context, som);
			toque.play();
		}
		catch(Exception e){}
	}
}