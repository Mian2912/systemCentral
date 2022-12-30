export class User{


    constructor(
	public id:number,
	public name:string,
	public lastname:string,
	public type_document:string,
	public document:string,
	public phone:string,
	public email:string,
	public password:string,
	public newpass:string,
	public equalpass:string
    ){}
}
