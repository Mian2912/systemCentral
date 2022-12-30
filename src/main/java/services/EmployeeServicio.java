/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package services;

import helpers.Helpers;
import java.io.IOException;
import java.net.HttpURLConnection;
import java.util.ArrayList;
import java.util.List;
import models.Employee;
import org.json.simple.JSONObject;
import org.json.simple.parser.ParseException;

/**
 *
 * @author migue
 */
public class EmployeeServicio{
    
        
    public Employee getToken(Employee employee) {
        
        try{
            // realizar conexion          
            HttpURLConnection conn = Helpers.updateORCreate("employee/login", "POST");

            // obteniendo parametros
            String paramaeters = "json="+employee.toString();

            JSONObject jsonObject = Helpers.getResponseWithPostAndPut(conn, paramaeters);
            employee.setToken(jsonObject.get("singup").toString()); 
            
        }catch(IOException | ParseException ex){
            System.out.println("error : " + ex.getMessage());
        }
        
        return employee;
    }

    public Employee getEmployee(Employee employee) {
        
        try{
            // realizar conexion          
            HttpURLConnection conn = Helpers.updateORCreate("employee/login", "POST");

            // obteniendo parametros
            employee.setGettoken("true");
            String parameters = "json="+employee.toString();

            JSONObject jsonObject = Helpers.getResponseWithPostAndPut(conn, parameters);
            JSONObject datos = (JSONObject) jsonObject.get("singup");
            JSONObject original = (JSONObject) datos.get("original");
            
            if(datos.containsKey("original")){
                employee.setAlert(original.get("message").toString());
                return employee;
            }
                
            employee.setId(Integer.parseInt(datos.get("sub").toString()));
            employee.setName(datos.get("name").toString());
            employee.setLastname(datos.get("lastname").toString());
            employee.setType_document(datos.get("type_document").toString());
            employee.setDocument(datos.get("document").toString());
            employee.setPhone(datos.get("phone").toString());
            employee.setEmail(datos.get("email").toString());
            employee.setId_rol(Integer.parseInt(datos.get("rol").toString()));
            
        }catch(IOException | ParseException ex){
            System.out.println("error: " + ex.getMessage());
        }
        
        return employee;
    }
    
    public List<Employee> getEmployees(Employee admin) {
        
        List<Employee> employees = new ArrayList<>();
        
        try{
                        
            HttpURLConnection conn = Helpers.getQueriesWithoutParameters("admin/employees");
            conn.setRequestProperty("Authorization", admin.getToken());
            
            JSONObject jsonObject = Helpers.getResponse(conn);
            
            List<JSONObject> empleados = (List<JSONObject>) jsonObject.get("employess");

            for (JSONObject empleado : empleados) {
               
                Employee employee = new Employee();
                employee.setId(Integer.parseInt(empleado.get("id").toString()));
                employee.setName(empleado.get("name").toString());
                employee.setLastname(empleado.get("lastname").toString());
                employee.setType_document(empleado.get("type_document").toString());
                employee.setDocument(empleado.get("document").toString());
                employees.add(employee);
                
            }
            
        }catch(IOException | ParseException ex){
            System.out.println("error: "+ex.getMessage());
        }
        
        return employees;
    }

    public Employee getEmployeeById(Employee employee, Employee admin) {
        
        try{          
            HttpURLConnection conn = Helpers.getQueriesWithoutParameters("admin/employee/"+employee.getId());
            conn.setRequestProperty("Authorization", admin.getToken());
           
            JSONObject jsonObject = Helpers.getResponse(conn);
            JSONObject datos = (JSONObject) jsonObject.get("employee");
            
            employee.setName(datos.get("name").toString());
            employee.setLastname(datos.get("lastname").toString());
            employee.setType_document(datos.get("type_document").toString());
            employee.setDocument(datos.get("document").toString());
            employee.setPhone(datos.get("phone").toString());
            employee.setEmail(datos.get("email").toString());
            
            
        }catch(IOException | ParseException ex){
            System.out.println("error: " + ex.getMessage());
        }
        
        return employee;
    }
    
    public Employee updateEmployee(Employee employee, Employee admin){
        
        try{
            HttpURLConnection conn = Helpers.updateORCreate("admin/employee/"+employee.getId(), "PUT");
            conn.setRequestProperty("Authorization", admin.getToken());
            
            String parameters = "json="+employee.json();
            JSONObject jsonObject = Helpers.getResponseWithPostAndPut(conn, parameters);
            employee.setAlert(jsonObject.get("message").toString());

        }catch(IOException | ParseException ex){
            System.out.println("error: " + ex.getMessage());
        }
        
        return employee;
    }
    
    public Employee newEmployee(Employee employee, Employee admin){
        
        try{            
            HttpURLConnection conn = Helpers.updateORCreate("admin/register", "POST");
            conn.setRequestProperty("Authorization", admin.getToken());

            String parameters = "json="+employee.json();
            JSONObject data = Helpers.getResponseWithPostAndPut(conn, parameters);
            
            employee.setAlert(data.get("message").toString());
            
        }catch(IOException | ParseException ex){
            System.out.println("error: "+ ex.getMessage());
        }
        
        return employee;
    }
    
    public Employee deleteEmployee(Employee employee, Employee admin){
        
        try{
            HttpURLConnection conn = Helpers.delete("admin/employee/"+employee.getId());
            conn.setRequestProperty("Authorization", admin.getToken());
            
            JSONObject json = Helpers.getResponse(conn);
            
            employee = new Employee();
            employee.setAlert(json.get("message").toString());
            
        }catch(IOException | ParseException ex){
            System.out.println("error: "+ex.getMessage());
        }
        return employee;
    }
    
    public Employee profile(Employee admin){
        
        try{
            HttpURLConnection conn = Helpers.updateORCreate("admin/profile/"+admin.getId(), "PUT");
            conn.setRequestProperty("Authorization", admin.getToken());
            
            String parameters = "json="+admin.json();
            JSONObject json = Helpers.getResponseWithPostAndPut(conn, parameters);
            
            admin.setAlert(json.get("message").toString());
        }catch(IOException | ParseException ex){
            System.out.println("error: "+ ex.getMessage());
        }
        return admin;
    }
    
    public Employee profileEmployee(Employee employee){
        
        try{
            HttpURLConnection conn = Helpers.updateORCreate("employee/update/"+employee.getId(), "PUT");
            conn.setRequestProperty("Authorization", employee.getToken());
            
            String parameters = "json="+employee.json();
            JSONObject data = Helpers.getResponseWithPostAndPut(conn, parameters);
            
            employee.setAlert(data.get("message").toString());
            
        }catch(IOException | ParseException ex){
            System.out.println("error: " + ex.getMessage());
            
        }
        
        return employee;
    }
}