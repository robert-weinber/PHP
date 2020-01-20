function formhash(form, password) {
    // Create a new element input, this will be our hashed password field. 
    var p = document.createElement("input");
 
    // Add the new element to our form. 
    form.appendChild(p);
    p.name = "p";
    p.type = "hidden";
    p.value = hex_sha512(password.value);
 
    // Make sure the plaintext password doesn't get sent. 
    password.value = "";
 
    // Finally submit the form. 
    form.submit();
}
 
function regformhash(form, last, first, email, organ, password, conf) {
     // Check each field has a value
    if (first.value == ''         || 
          last.value == ''     || 
		  email.value == ''     || 
		  organ.value == ''     || 
          password.value == ''  || 
          conf.value == '') {
 
        alert('Minden mezőt ki kell töltenie. Kérjük próbálja újra!');
        return false;
    }
 
    // Check the username
 
    re = /^[a-zA-Záéíóöőúüű]*$/; 
	// /^\w+$/; 
	// /^[a-zA-Záéíóöőúüű]*$/;
    if(!re.test(form.lastname.value)) { 
        alert("A vezetéknév csak kis- és nagybetűket tartalmazhat. Próbálja újra!"); 
        form.lastname.focus();
        return false; 
    }
	if(!re.test(form.firstname.value)) { 
        alert("A keresztnév csak kis- és nagybetűket tartalmazhat. Próbálja újra!"); 
        form.firstname.focus();
        return false; 
    }
 
    // Check that the password is sufficiently long (min 6 chars)
    // The check is duplicated below, but this is included to give more
    // specific guidance to the user
    if (password.value.length < 6) {
        alert('A jelszónak legalább 6 karakter hosszúnak kell lennie.  Kérjük próbálja újra!');
        form.password.focus();
        return false;
    }
 
    // At least one number, one lowercase and one uppercase letter 
    // At least six characters 
 
    var re = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}/; 
    if (!re.test(password.value)) {
        alert('A jelszavának legalább egy számot, egy kisbetűt és egy nagybetűt kell tartalmaznia.  Kérjük próbálja újra!');
        return false;
    }
 
    // Check password and confirmation are the same
    if (password.value != conf.value) {
        alert('A jelszónak és a megerősítésének pontosan meg kell egyeznie. Kérjük próbálja újra!');
        form.password.focus();
        return false;
    }
 
    // Create a new element input, this will be our hashed password field. 
    var p = document.createElement("input");
 
    // Add the new element to our form. 
    form.appendChild(p);
    p.name = "p";
    p.type = "hidden";
    p.value = hex_sha512(password.value);
 
    // Make sure the plaintext password doesn't get sent. 
    password.value = "";
    conf.value = "";
 
    // Finally submit the form. 
    form.submit();
    return true;
}

function changeformhash(form, oldpw, oldconf/*, newpw, newconf*/) {
	 // Check each field has a value
    if (oldpw.value == ''         || 
          oldconf.value == ''     
		  //|| 
          //newpw.value == ''  || 
          //newconf.value == ''
		  ) {
 
        alert('Adja meg jelenlegi jelszavát az adatok módosításához!');
        return false;
    }
		// Check password and confirmation are the same
    if (oldpw.value != oldconf.value) {
        alert('A jelenlegi jelszónak és a megerősítésének pontosan meg kell egyeznie. Kérjük próbálja újra!');
        return false;
    }
if(form.newpw.value !== ''){
	// Check that the password is sufficiently long (min 6 chars)
    // The check is duplicated below, but this is included to give more
    // specific guidance to the user
    if (form.newpw.value.length < 6) {
        alert('A jelszónak legalább 6 karakter hosszúnak kell lennie.  Kérjük próbálja újra!');
        form.newpw.focus();
        return false;
    }
 
    // At least one number, one lowercase and one uppercase letter 
    // At least six characters 
 
    var re = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}/; 
    if (!re.test(form.newpw.value)) {
        alert('A jelszavának legalább egy számot, egy kisbetűt és egy nagybetűt kell tartalmaznia.  Kérjük próbálja újra!');
        return false;
    }
 
    // Check password and confirmation are the same
    if (form.newpw.value != form.newconf.value) {
        alert('Az új jelszónak és a megerősítésének pontosan meg kell egyeznie. Kérjük próbálja újra!');
        form.newpw.focus();
        return false;
    }
	var p = document.createElement("input");
 
    // Add the new element to our form. 
    p.name = "p";
    p.type = "hidden";
    p.value = hex_sha512(newpw.value);
}
var re = /^[a-zA-Záéíóöőúüű]*$/; 
if(form.vname.value !== ''){
	if(!re.test(form.vname.value)) { 
        alert("A vezetéknév csak kis- és nagybetűket tartalmazhat. Próbálja újra!"); 
        form.lastname.focus();
        return false; 
    }
	var vn = document.createElement("input");
 
    // Add the new element to our form. 
    vn.name = "vn";
    vn.type = "hidden";
    vn.value = form.vname.value;
}
if(form.kname.value !== ''){	
	if(!re.test(form.kname.value)) { 
        alert("A keresztnév csak kis- és nagybetűket tartalmazhat. Próbálja újra!"); 
        form.firstname.focus();
        return false; 
    }
	var kn = document.createElement("input");
 
    // Add the new element to our form. 
    kn.name = "kn";
    kn.type = "hidden";
    kn.value = form.kname.value;
}
re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

if(form.emailad.value !== ''){
	if(!re.test(form.emailad.value)) { 
        alert("Az Email-cím formátuma érvénytelen. Próbálja újra!"); 
        form.emailad.focus();
        return false; 
    }
	var em = document.createElement("input");
 
    // Add the new element to our form. 
    em.name = "em";
    em.type = "hidden";
    em.value = form.emailad.value;
}
if(form.organ.value !== ''){
	var org = document.createElement("input");
 
    // Add the new element to our form. 
    org.name = "org";
    org.type = "hidden";
    org.value = form.organ.value;
}

 
    // Create a new element input, this will be our hashed password field. 
	if(typeof p !== 'undefined')
    form.appendChild(p);
	if(typeof vn !== 'undefined')
    form.appendChild(vn);
	if(typeof kn !== 'undefined')
    form.appendChild(kn);
	if(typeof em !== 'undefined')
    form.appendChild(em);
	if(typeof org !== 'undefined')
    form.appendChild(org);
	
	var op = document.createElement("input");
 
    // Add the new element to our form. 
    form.appendChild(op);
    op.name = "op";
    op.type = "hidden";
    op.value = hex_sha512(oldpw.value);
 
 
    // Make sure the plaintext password doesn't get sent.
	form.vname.value = "";
    form.kname.value = "";
	form.emailad.value = "";
    form.organ.value = "";
    form.newpw.value = "";
    form.newconf.value = "";
	oldpw.value = "";
    oldconf.value = "";
 
    // Finally submit the form. 
    form.submit();
    return true;
}

function newformhash(form, newpw, newconf) {
	 // Check each field has a value
    if (newpw.value == ''  || 
          newconf.value == '') {
 
        alert('Minden mezőt ki kell töltenie. Kérjük próbálja újra!');
        return false;
    }
	// Check that the password is sufficiently long (min 6 chars)
    // The check is duplicated below, but this is included to give more
    // specific guidance to the user
    if (newpw.value.length < 6) {
        alert('A jelszónak legalább 6 karakter hosszúnak kell lennie.  Kérjük próbálja újra!');
        form.newpw.focus();
        return false;
    }
 
    // At least one number, one lowercase and one uppercase letter 
    // At least six characters 
 
    var re = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}/; 
    if (!re.test(newpw.value)) {
        alert('A jelszavának legalább egy számot, egy kisbetűt és egy nagybetűt kell tartalmaznia.  Kérjük próbálja újra!');
        return false;
    }
 
    // Check password and confirmation are the same
    if (newpw.value != newconf.value) {
        alert('Az új jelszónak és a megerősítésének pontosan meg kell egyeznie. Kérjük próbálja újra!');
        form.newpw.focus();
        return false;
    }
	
	
 
    // Create a new element input, this will be our hashed password field. 
    var p = document.createElement("input");
 
    // Add the new element to our form. 
    form.appendChild(p);
    p.name = "p";
    p.type = "hidden";
    p.value = hex_sha512(newpw.value);
	
	
 
    // Make sure the plaintext password doesn't get sent. 
    newpw.value = "";
    newconf.value = "";
 
    // Finally submit the form. 
    form.submit();
    return true;
}