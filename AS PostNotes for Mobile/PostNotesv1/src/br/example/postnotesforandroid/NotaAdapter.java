package br.example.postnotesforandroid;

import java.util.ArrayList;

import android.content.Context;
import android.graphics.Color;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.TextView;

public class NotaAdapter extends BaseAdapter {

	private Context context;
	private ArrayList<Nota> lista;
	
	public NotaAdapter(Context context, ArrayList<Nota> lista){
		this.context = context;
		this.lista = lista;
	}
	
	@Override
	public int getCount() {
		// TODO Auto-generated method stub
		return lista.size();
	}

	@Override
	public Object getItem(int position) {
		// TODO Auto-generated method stub
		return lista.get(position);
	}

	@Override
	public long getItemId(int position) {
		// TODO Auto-generated method stub
		return position;
	}

	@Override
	public View getView(int position, View convertView, ViewGroup parent) {

		Nota nota = lista.get(position);
		View layout;
		if (convertView == null) {
			LayoutInflater inflater = (LayoutInflater) context.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
			layout = inflater.inflate(R.layout.activity_note_info , null);
		} else {
			layout = convertView;
		}
		
		
		TextView texto = (TextView) layout.findViewById(R.id.textoNota);
		texto.setText(nota.getTexto());
		
		TextView dono = (TextView) layout.findViewById(R.id.donoNota);
		dono.setText(nota.getDono());
		
		return layout;
	}

}
