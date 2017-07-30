package br.example.postnotesforandroid;

import java.util.Calendar;

import android.app.AlarmManager;
import android.app.PendingIntent;
import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;
import android.util.Log;

public class BroadcastClock extends BroadcastReceiver {

	private static final String ALARM_SERVICE = "alarm";

	@Override
	public void onReceive(Context context, Intent intent1) {
		// TODO Auto-generated method stub
		Log.i("Script","Instalado");
		Intent it = new Intent(context, ClockerActivity.class);
		it.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
		context.startActivity(it);
		
	}
}