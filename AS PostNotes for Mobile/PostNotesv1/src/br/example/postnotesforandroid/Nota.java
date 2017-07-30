package br.example.postnotesforandroid;

import android.annotation.SuppressLint;


@SuppressLint("SimpleDateFormat")
public class Nota {
	private String id;
	private String texto;
	private String dono;
	private String alarm;
	private String date;
	
	
	/*
	public Nota(int id = 0, String texto = '', String dono = '', String alarm = '', String date = '') throws ParseException {
		super();
		this.id = id;
		this.texto = texto;
		this.dono = dono;
		
		SimpleDateFormat formatter = new SimpleDateFormat("dd-MM-yyyy hh:mm:ss aa");
		Date datee = (Date)formatter.parse(alarm);
		this.alarm = datee;
		
		SimpleDateFormat formatterr = new SimpleDateFormat("dd-MM-yyyy hh:mm:ss aa");
		Date dateee = (Date)formatterr.parse(date);
		this.date = dateee;
	}
	*/
	
	
	
	
	public Nota(String id, String texto, String dono, String alarm, String date) {
		super();
		this.id = id;
		this.texto = texto;
		this.dono = dono;
		this.alarm = alarm;
		this.date = date;
	}
	
	public String getId() {
		return id;
	}
	public void setId(String id) {
		this.id = id;
	}
	public String getTexto() {
		return texto;
	}
	public void setTexto(String texto) {
		this.texto = texto;
	}
	public String getDono() {
		return dono;
	}
	public void setDono(String dono) {
		this.dono = dono;
	}
	public String getAlarm() {
		return alarm;
	}
	public void setAlarm(String alarm) {
		this.alarm = alarm;
	}
	public String getDate() {
		return date;
	}
	public void setDate(String date) {
		this.date = date;
	}
	
	
	
}
