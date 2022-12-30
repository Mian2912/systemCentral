/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package controllers;


import java.util.List;
import java.util.regex.Matcher;
import java.util.regex.Pattern;
import javax.swing.table.DefaultTableModel;
import models.Employee;
import services.EmployeeServicio;

/**
 *
 * @author migue
 */
public class EmployeeController {
    
    public Employee login(Employee employee){
        
        // validando el correo vacio
        if(employee.getEmail().equals("")){
            employee.setAlert("Ingrese el correo electronico");
            return employee;
        }
        
        if(employee.getPassword().equals("")){
            employee.setAlert("Ingrese la contraseña");
            return employee;
        }
        
        Pattern pattern = Pattern.compile("^[\\w-]+(\\.[\\w-]+)*@[A-Za-z0-9]+(\\.[A-Za-z0-9]+)*(\\.[A-Za-z]{2,})$");
        Matcher matcher = pattern.matcher(employee.getEmail());
        if(!matcher.find()){
            employee.setAlert("correo invalido, intentelo de nuevo");
            return employee;
        }
        
        pattern = Pattern.compile("[0-9a-zA-Z]{8,12}");
        matcher = pattern.matcher(employee.getPassword());
        if(!matcher.find()){
            employee.setAlert("La Contraseña es invalida, debe contener de 8 a 12 caracteres");
            return employee;
        }
        
        // enviando los datos y obteniendo el token
        EmployeeServicio service = new EmployeeServicio();        
        employee = service.getToken(employee);

        // obteniendo los datos decodificados del empleado
        employee = service.getEmployee(employee);
                
        return employee;
    }
    
    public DefaultTableModel getEmployees(Employee employee){
        
        String[] title = {"id", "No", "Nombres", "Tipo de documento", "Documento"};
        String[] rows = new String[5];
        DefaultTableModel model = new DefaultTableModel(null, title);
        
        //creando array de empleados
        List<Employee> employees = new EmployeeServicio().getEmployees(employee);
        int i = 1;
        // recorriendo el array de empleados
        for (Employee employee1 : employees) {
            rows[0] = String.valueOf(employee1.getId());
            rows[1] = String.valueOf(i++);
            rows[2] = employee1.getName() + " " + employee1.getLastname();
            rows[3] = employee1.getType_document();
            rows[4] = employee1.getDocument();
            model.addRow(rows);
        }
        
        return model;
    }

    public Employee newEmployee(Employee employee, Employee admin){
        
        if(employee.getName().length() < 3){
            employee.setAlert("Ingrese el nombre del empleado");
            return employee;
        }
        
        if(employee.getLastname().length() < 3){
            employee.setAlert("Ingrese los apellidos del empleado");
            return employee;
        }
        
        if(employee.getType_document().equalsIgnoreCase("Seleccione tipo de documento....")){
            employee.setAlert("Seleccione tipo de documento");
            return employee;
        }
        
        if(employee.getDocument().length() < 7 || employee.getDocument().length() > 20){
            employee.setAlert("Ingrese el numero de documento");
            return employee;
        }
        
        if(employee.getPhone().length() >10 || employee.getPhone().length() < 10){
            employee.setAlert("ingrese numero de telefono");
            return employee;
        }
        
        if(employee.getEmail().length() < 3){
            employee.setAlert("Ingrese el correo electronico");
            return employee;
        }
        
        if(employee.getPassword().length() < 8 || employee.getPassword().length() >12){
            employee.setAlert("ingrese la contraseña");
            return employee;
        }
        
        employee = new EmployeeServicio().newEmployee(employee, admin);
        return employee;
    }
}