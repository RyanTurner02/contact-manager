const urlBase = 'http://www.cop4331group15.social/LAMPAPI';
const extension = 'php';

let userId = 0;
let firstName = "";
let lastName = "";
let contacts;

/*
	description: logs user in to the Contact manager app.
	posts: username, password
	retrieves: User object
*/
function doLogin()
{
	userId = 0;
	firstName = "";
	lastName = "";
	
	let login = document.getElementById("username").value;
	let password = document.getElementById("password").value;

	console.log(`login: ${login}\npassword: ${password}`);

	document.getElementById("loginResult").innerHTML = "";

	let tmp = {login:login,password:password};
	let jsonPayload = JSON.stringify( tmp );
	
	let url = urlBase + '/Login.' + extension;

	let xhr = new XMLHttpRequest();
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	try
	{
		xhr.onreadystatechange = function() 
		{
			if (this.readyState == 4 && this.status == 200) 
			{
				let jsonObject = JSON.parse( xhr.responseText );
				userId = jsonObject.id;
		
				if( userId < 1 )
				{		
					document.getElementById("loginResult").innerHTML = "Invalid username or password";
					return;
				}
		
				firstName = jsonObject.firstName;
				lastName = jsonObject.lastName;

				saveCookie();
	
				window.location.href = "contacts.html";
			}
		};
		xhr.send(jsonPayload);
	}
	catch(err)
	{
		document.getElementById("loginResult").innerHTML = err.message;
	}
}

/*
	description: Creates and inserts a new user on to the database
	posts: firstname, lastname, username, password
	retrieves: User object
*/
function doRegister() {
	firstName = document.getElementById("firstname").value;
	lastName = document.getElementById("lastname").value;
	let login = document.getElementById("username").value;
	let password = document.getElementById("password").value;

	// todo: input validation

	let user = {
		firstName: firstName,
		lastName: lastName,
		login: login,
		password: password
	};

	let jsonPayload = JSON.stringify(user);

	let url = urlBase + '/register.' + extension;

	let xhr = new XMLHttpRequest();
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	try
	{
		xhr.onreadystatechange = function()
		{
			if(this.status == 409)
			{
				document.getElementById("loginResult").innerHTML = "Invalid username";
				return;
			}

			if (this.readyState == 4 && this.status == 200)
			{
				doLogin();
			}
		};
		xhr.send(jsonPayload);
	}
	catch(err)
	{
		document.getElementById("loginResult").innerHTML = err.message;
	}
}

/*
	description: Saves a cookie on to the Users access point.
	posts: N/A
	retrieves: N/A
*/
function saveCookie()
{
	let minutes = 20;
	let date = new Date();
	date.setTime(date.getTime()+(minutes*60*1000));	
	document.cookie = "firstName=" + firstName + ",lastName=" + lastName + ",userId=" + userId + ";expires=" + date.toGMTString();
}

/*
	description: reads cookie from the Users access point.
	posts: N/A
	retrieves: N/A
*/
function readCookie()
{
	userId = -1;
	let data = document.cookie;
	let splits = data.split(",");
	for(var i = 0; i < splits.length; i++) 
	{
		let thisOne = splits[i].trim();
		let tokens = thisOne.split("=");
		if( tokens[0] == "firstName" )
		{
			firstName = tokens[1];
		}
		else if( tokens[0] == "lastName" )
		{
			lastName = tokens[1];
		}
		else if( tokens[0] == "userId" )
		{
			userId = parseInt( tokens[1].trim() );
		}
	}
	
	if( userId < 0 )
	{
		window.location.href = "index.html";
	}
	else
	{
		//document.getElementById("userName").innerHTML = "Logged in as " + firstName + " " + lastName;
	}
}

/*
	description: redirects user back to the home page (index.html).
	posts: N/A
	retrieves: N/A
*/
function doLogout()
{
	userId = 0;
	firstName = "";
	lastName = "";
	document.cookie = "firstName= ; expires = Thu, 01 Jan 1970 00:00:00 GMT";
	window.location.href = "index.html";
}


/*
	description: Creates and inserts a new contact to the database.
	posts: Name, email, phone number
	retrieves: new Contact
*/
function addContact() {
	// Regex for validation
	const validName = /^([a-zA-Z])/;
	const validEmail = /^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/;
	const validPhone = /^[0-9]/;

	// getting input from input feilds in the html
    let firstName_in = document.getElementById("contactTextFirst");
    let lastName_in = document.getElementById("contactTextLast");
    let phoneNumber_in = document.getElementById("contactTextNumber");
    let emailAddress_in = document.getElementById("contactTextEmail");

	let firstName = firstName_in.value;
	let lastName = lastName_in.value;
	let phoneNumber = phoneNumber_in.value;
	let emailAddress = emailAddress_in.value;

	let validFields = true;
	
	// Validation Checks
	/// First Name Validation
	if (firstName == "" ) {
		console.log("Empty First Name");
		firstName_in.className = "incorrect";
		firstName_in.placeholder = "Empty First Name";
		validFields = false;
	}
    else if (!validName.test(firstName)) {
		console.log("invalid First Name");
		firstName_in.text = "";
		firstName_in.className = "incorrect";
		firstName_in.placeholder = "Invalid First Name";
		validFields = false;
	}
	else {
		firstName_in.placeholder = "First Name";
		firstName_in.className = "form-control";
	}

	/// Last Name Validation
	if (lastName == "" ) {
		console.log("Empty Last Name");
		lastName_in.className = "incorrect";
		lastName_in.placeholder = "Empty Last Name";
		validFields = false;
	}
    else if (!validName.test(lastName)) {
		console.log("invalid Email");
		lastName_in.className = "incorrect";
		lastName_in.placeholder = "Invalid Last Name";
		validFields = false;
	}
	else {
		lastName_in.placeholder = "Last Name";
		lastName_in.className = "form-control";
	}

	/// Email Validation
	if (emailAddress == "" ) {
		console.log("Empty Email");
		emailAddress_in.className = "incorrect";
		emailAddress_in.placeholder = "Empty Email Address";
		validFields = false;
	}
    else if (!validEmail.test(emailAddress)) {
		console.log("invalid Email");
		emailAddress_in.className = "incorrect";
		emailAddress_in.placeholder = "Invalid Email Address";
		validFields = false;
	}
	else {
		emailAddress_in.placeholder = "name@email.com";
		emailAddress_in.className = "form-control";
	}

	/// Phone Number Validation
	if (phoneNumber == "" ) {
		console.log("Empty Phone Number");
		phoneNumber_in.className = "incorrect";
		phoneNumber_in.placeholder = "Empty Phone Number";
		validFields = false;
	}
    else if (!validPhone.test(phoneNumber)) {
		console.log("Invalid Phone Number (digits)");
		phoneNumber_in.className = "incorrect";
		phoneNumber_in.placeholder = "Only use digits 0-9";
		validFields = false;
	}
	else if (phoneNumber.length != 10) {
		console.log("Invalid Phone Number (length)");
		phoneNumber_in.className = "incorrect";
		phoneNumber_in.placeholder = "Must only contain 10 digits";
		validFields = false;
	}
	else {
		phoneNumber_in.placeholder = "(XXX) XXX-XXX";
		phoneNumber_in.className = "form-control";
	}

	// Checks if any of the validation checks were unsuccessful
	if (!validFields)
		return;

    let tmp = {
        Name: firstName + "_" + lastName,
        Email: emailAddress,
        Phone: phoneNumber,
        UserID: userId
    };


    let jsonPayload = JSON.stringify(tmp);

    let url = urlBase + '/Create.' + extension;

    let xhr = new XMLHttpRequest();
    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
    try {
        xhr.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                // Clear input fields in form 
                document.getElementById("addForm").reset();
                // reload contacts table and switch view to show
                window.location.href = "contacts.html";
            }
        };
        console.log("Full Name: " + tmp.Name + " Phone: " + tmp.Phone + " Email: " + tmp.Email);
        xhr.send(jsonPayload);
    } catch (err) {
        document.getElementById("contactAddResult").innerHTML = err.message;
		console.log("Happened here");
    }
}

/*
	description: refreshes the table in the users screen, and loads first 10 contacts to it.
	posts: N/A
	retrieves: N/A
*/
function refreshContacts() {
	let searchBar = document.getElementById("searchBar");
	searchBar.value = "";
	loadContacts();
}

/*
	description: loads contacts from the data base.
	posts: UserID
	retrieves: array of related objects
*/
function loadContacts()
{

  let url = urlBase + '/SearchContacts.' + extension;
 	let xhr = new XMLHttpRequest();
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
 
 	let tmp = {
   UserID: userId
  };
  
	let jsonPayload = JSON.stringify( tmp );
	console.log(jsonPayload);

  try 
  {
    xhr.onreadystatechange = function() 
    {
      if (this.readyState == 4 && this.status == 200)
      {
        let jsonObject = JSON.parse( xhr.responseText );
        console.log("Contacts have been loaded.");
        console.log(jsonObject.length);
        for(let i=0; i<jsonObject.length; i++)
        {
          console.log(jsonObject[i]);
        }
		contacts = jsonObject;
		populateTable();
      }
    };
    xhr.send(jsonPayload);
  }
 	catch(err)
	{
		document.getElementById("colorSearchResult").innerHTML = err.message;
   		console.log("Error caught");
	}
}

/*
	description: prints first n contacts in the contacts array.
	posts: N/A
	retrieves: N/A
*/
function populateTable() {
	let table = document.getElementById("contactsTable");
	table.innerHTML = "";
	console.log(contacts.length);
	// table.innerHTTL = ""; // Banks table between searches

	table = document.createElement("table");
	table.setAttribute("class", "table");

	let first_row = table.insertRow(0);
	first_row.innerHTML = "<th>First Name</th>\n<th>Last Name</th>\n<th>Email Address</th>\n<th>Phone Number</th><th></th>";

	
	document.getElementById("contactsTable").appendChild(table);

	if (contacts.construtor !== Array && contacts.id == 0 && contacts.firstName == '') {
		let errorRow = table.insertRow(1);

		for (let i = 0; i < 5; i++)
		{
			let message = errorRow.insertCell(i);
			if (i == 0)
				message.innerHTML = "No Contacts Found";
		}
		
		return;
	}

	let length = (contacts.length < 10) ? contacts.length : 10;
	for (let i = 0; i < length; i++) {
		if (contacts[i] != null) {
			let row = table.insertRow(i + 1);
			let name = contacts[i].Name.split("_");

			let fname;
			let lname;
			fname = name[0];
			lname = (name.length < 2) ? "[N/A]" : name[1];

			fname_r = row.insertCell(0);
			fname_r.innerHTML = '<div id="firstName' + i + '">' + fname + '</div>';

			lname_r = row.insertCell(1);
			lname_r.innerHTML = '<div id="lastName' + i + '">' + lname + '</div>';
			
			let email = row.insertCell(2);
			email.innerHTML = '<div id="email' + i + '">' + contacts[i].Email + '</div>';

			let phone = row.insertCell(3);
			let phoneNum = "(";
			let index = 0;
			for (index; index < 3; index++) {
				phoneNum += contacts[i].Phone.charAt(index);
			}

			phoneNum += ") ";

			for (index; index < 6; index++) {
				phoneNum += contacts[i].Phone.charAt(index);
			}

			phoneNum += "-";

			for (index; index < 10; index++) {
				phoneNum += contacts[i].Phone.charAt(index);
			}
			phone.innerHTML = '<div id="phone' + i + '">' + phoneNum + '</div>';
			
			let Buttons = row.insertCell(4);
			Buttons.innerHTML = '<div class="btn-group d-flex justify-content-end">' + 
									'<editButton class="btn btn-outline-primary" id="edit_button' + i + '" onclick="editContactPreview(' + i + ')">Edit</editButton>' +
                  '<saveButton class="btn btn-outline-primary" id="save_button' + i + '" onclick="saveEdittedContact(' + i + ')" style="display: none">Confirm</saveButton>' +
                  '<cancelButton class="btn btn-outline-danger" id="cancel_button' + i + '" onclick="populateTable()" style="display: none">Cancel</cancelButton>' +
									'<deleteButton class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modal" id="delete_button' + i + '" onClick = "deleteContact(' + i + ')">Delete</deleteButton>' +
								'</div>';
		}
		else {
			break;
		}
	}
}

/*
	description: deletes a contact from the database.
	posts: firstname, lastname, email, phone number, UserID
	retrieves: info of deleted contact
*/
function deleteContact(index) {
    // user clicks outside modal
    let confirmationModal = document.getElementById('modal');
    confirmationModal.addEventListener('hidden.bs.modal', function (event) {
        window.location.href = "contacts.html";
    })

    // user clicks on cancel button
    let cancelButton = document.getElementById("deleteCancel").addEventListener("click", function() {
        window.location.href = "contacts.html";
    });
    
    // user clicks on confirm button
    let confirmationButton = document.getElementById("deleteConfirmation").addEventListener("click", function() {
        let url = urlBase + '/Delete.' + extension;
        let xhr = new XMLHttpRequest();
        xhr.open("Post", url, true);
        xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
        
        let tmp = {
            Name : contacts[index].Name,
            Email : contacts[index].Email,
            Phone : contacts[index].Phone,
    	      UserID : userId
        };
        
      	let jsonPayload = JSON.stringify( tmp );
    	  console.log(jsonPayload);
     
        try {
            xhr.onreadystatechange = function() {
    
    		    if (xhr.readyState == 4 && xhr.status == 200) {
    			      console.log("Contact has been deleted");
                window.location.href = "contacts.html";
                }
            };
            
            xhr.send(jsonPayload);
        }
        catch (err) {
            console.log("Error Caught: " + err);
        } 
    });
}

/*
	description: retrieves an array of contacts that contains the wanted string.
	posts: Name/query, UserID
	retrieves: array of Contacts
*/
function searchContacts() {
	let url = urlBase + '/SearchColors.' + extension;
	let xhr = new XMLHttpRequest();
	let search = document.getElementById("searchBar").value;

	let name = search.trim();
	name = name.replace(" ", "");
   	xhr.open("POST", url, true);
   	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");

	let tmp = {
		Name : name,
  		UserID: userId
 	};
 
   let jsonPayload = JSON.stringify( tmp );
   console.log(jsonPayload);

 try 
 {
   xhr.onreadystatechange = function() 
   {
	 if (this.readyState == 4 && this.status == 200)
	 {
	   let jsonObject = JSON.parse( xhr.responseText );
	   console.log("Contacts have been loaded.");
	   console.log(jsonObject.length);
	   for(let i=0; i<jsonObject.length; i++)
	   {
		 console.log(jsonObject[i]);
	   }
	   contacts = jsonObject;
	   console.log("Searching Contacts...");
	   populateTable();
	 }
   };
   xhr.send(jsonPayload);
 }
	catch(err)
   {
	   document.getElementById("colorSearchResult").innerHTML = err.message;
		  console.log("Error caught");
   }	
}


// Edits the selected contact and refreshes the contact afterwards
function editContactPreview(index) {
    // Change the display of the row
    document.getElementById("edit_button" + index).style.display = "none";
    document.getElementById("save_button" + index).style.display = "inline-block";
    document.getElementById("delete_button" + index).style.display = "none";
    document.getElementById("cancel_button" + index).style.display = "inline-block";
    
    
    var firstNameI = document.getElementById("firstName" + index);
    var lastNameI = document.getElementById("lastName" + index);
    var email = document.getElementById("email" + index);
    var phone = document.getElementById("phone" + index);
  
    let tmp = {
        Name : contacts[index].Name,
        Email : contacts[index].Email,
        Phone : contacts[index].Phone,
	      UserID : userId
    };
    
    // Split the Name var into first and last
    let name = tmp.Name.split("_");
		let fname;
		let lname;
		fname = name[0];
		lname = (name.length < 2) ? "[N/A]" : name[1];
   
   // Replace the entry with forms to allow the user to enter in new data
    firstNameI.innerHTML = '<input type="text" class="form-control" id="fUpdate' + index + '" value="' + fname + '">';
    lastNameI.innerHTML  = '<input type="text" class="form-control" id="lUpdate' + index + '" value="' + lname + '">';
    email.innerHTML      = '<input type="text" class="form-control" id="eUpdate' + index + '" value="' + tmp.Email + '">';
    phone.innerHTML      = '<input type="text" class="form-control" id="pUpdate' + index + '" value="' + tmp.Phone + '">';
    
}

// Do the actual updating of the contact once the save button has been pressed
function saveEdittedContact(index) {

    var fname_val = document.getElementById("fUpdate" + index).value;
    var lname_val = document.getElementById("lUpdate" + index).value;
    var email_val = document.getElementById("eUpdate" + index).value;
    var phone_val = document.getElementById("pUpdate" + index).value;
       
    // Recombine the name
    var fullName = fname_val + "_" + lname_val;
    
    // Revert the buttons back to make edit visible
    document.getElementById("edit_button" + index).style.display = "inline-block";
    document.getElementById("save_button" + index).style.display = "none";
   
    let tmp = {
        Name : fullName,
        Email : email_val,
        Phone : phone_val,
        UserID : userId,
	      id : contacts[index].ID
    };
    
  	let jsonPayload = JSON.stringify(tmp);
    let url = urlBase + '/Update.' + extension;
    let xhr = new XMLHttpRequest();
    xhr.open("Post", url, true);
    xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
 
    try {
        xhr.onreadystatechange = function() {

		    if (xhr.readyState == 4 && xhr.status == 200) {
			            console.log("Contact has been updated");
                  loadContacts();
            	}
        };
        xhr.send(jsonPayload);
    }
    catch (err) {
        console.log("Error Caught: " + err);
    }
}


////////////// Leineker Code /////////////////
// function addColor()
// {
// 	let newColor = document.getElementById("colorText").value;
// 	document.getElementById("colorAddResult").innerHTML = "";

// 	let tmp = {color:newColor,userId,userId};
// 	let jsonPayload = JSON.stringify( tmp );

// 	let url = urlBase + '/AddColor.' + extension;
	
// 	let xhr = new XMLHttpRequest();
// 	xhr.open("POST", url, true);
// 	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
// 	try
// 	{
// 		xhr.onreadystatechange = function() 
// 		{
// 			if (this.readyState == 4 && this.status == 200) 
// 			{
// 				document.getElementById("colorAddResult").innerHTML = "Color has been added";
// 			}
// 		};
// 		xhr.send(jsonPayload);
// 	}
// 	catch(err)
// 	{
// 		document.getElementById("colorAddResult").innerHTML = err.message;
// 	}
	
// }

// function searchColor()
// {
// 	let srch = document.getElementById("searchText").value;
// 	document.getElementById("colorSearchResult").innerHTML = "";
	
// 	let colorList = "";

// 	let tmp = {search:srch,userId:userId};
// 	let jsonPayload = JSON.stringify( tmp );

// 	let url = urlBase + '/SearchColors.' + extension;
	
// 	let xhr = new XMLHttpRequest();
// 	xhr.open("POST", url, true);
// 	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
// 	try
// 	{
// 		xhr.onreadystatechange = function() 
// 		{
// 			if (this.readyState == 4 && this.status == 200) 
// 			{
// 				document.getElementById("colorSearchResult").innerHTML = "Color(s) has been retrieved";
// 				let jsonObject = JSON.parse( xhr.responseText );
				
// 				for( let i=0; i<jsonObject.results.length; i++ )
// 				{
// 					colorList += jsonObject.results[i];
// 					if( i < jsonObject.results.length - 1 )
// 					{
// 						colorList += "<br />\r\n";
// 					}
// 				}
				
// 				document.getElementsByTagName("p")[0].innerHTML = colorList;
// 			}
// 		};
// 		xhr.send(jsonPayload);
// 	}
// 	catch(err)
// 	{
// 		document.getElementById("colorSearchResult").innerHTML = err.message;
// 	}
	
// }
///////////////////////////////////////////////////////////////////////////////////