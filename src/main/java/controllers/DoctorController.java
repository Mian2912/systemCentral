/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package controllers;


import java.util.List;
import javax.swing.table.DefaultTableModel;
import models.Doctor;
import models.Employee;
import services.DoctorService;

/**
 *
 * @author migue
 */
public class DoctorController {
    
    public DefaultTableModel loadTable(){
        String[] title = {"id","No", "Nombres", "Tipo de documento", "Documento"};
        String[] rows = new String[5];
        DefaultTableModel model = new DefaultTableModel(null, title);
        List<Doctor> doctors = new DoctorService().getDoctors();

        int i = 1;
        for (Doctor doctor : doctors){
            rows[0] = String.valueOf(doctor.getId());
            rows[1] = String.valueOf(i++);
            rows[2] = doctor.getName() +" "+ doctor.getLastname();
            rows[3] = doctor.getType_document();
            rows[4] = doctor.getDocument();
            model.addRow(rows);
        }
        
        return model;   
    }

    public Doctor setAddDoctor(Doctor doctor, Employee admin){
        
        if(doctor.getName().length() < 3){
            doctor.setAlert("Ingrese los nombre");
            return doctor;
        }
        
        if(doctor.getLastname().length() < 3){
            doctor.setAlert("Ingrese los apellidos");
            return doctor;
        }
        
        if(doctor.getType_document().equalsIgnoreCase("Seleccione tipo de documento....")){
            doctor.setAlert("Seleccione tipo de documento");
            return doctor;
        }
        
        if(doctor.getDocument().length() < 6 || doctor.getDocument().length() >20){
            doctor.setAlert("Ingresse un documento valido");
            return doctor;
        }
        
        if(doctor.getPhone().length() < 10 || doctor.getPhone().length() > 10){
            doctor.setAlert("Ingrese un telefono valido");
            return doctor;
        }
        
        doctor = new DoctorService().setAddDoctor(doctor, admin);
        return doctor;
    }
}
