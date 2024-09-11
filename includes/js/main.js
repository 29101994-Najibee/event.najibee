function handleErrorResponse(error) {
    console.error('Error occurred:', error);
    
    var errorMessageElement = document.getElementById('errorMessage');
    errorMessageElement.textContent = 'An error occurred: ' + error.message;
    errorMessageElement.style.display = 'block';

   
    setTimeout(function() {
        errorMessageElement.style.display = 'none';
    }, 5000);
}

function handleSuccessResponse(data) {
    console.log('Response data:', data);

    var successMessageElement = document.getElementById('successMessage');
    successMessageElement.textContent = 'Operation successful!';
    successMessageElement.style.display = 'block';

    setTimeout(function() {
        successMessageElement.style.display = 'none';
    }, 5000);
}

function loginBtnFunction(event) {
    event.preventDefault();
    var submit = event.currentTarget;
    var action = submit.dataset.action;
    if (action === 'login') {
        var username = document.getElementById('username').value;
        var password = document.getElementById('password').value;
        fetch('/v1/user/login/', {
                method: 'POST',
                dataType:'json',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json; charset=utf-8'
                },
                body: JSON.stringify({
                    username: username,
                    password: password
                }),
                cache: 'default'
            })
            .then(response => response.json())
            .then(data => {
                console.log(data);
                if(data.user_type){
                  
                    showPage(data);  
                    handleSuccessResponse(data);
                }else{
                    handleErrorResponse(new Error('Login failed: ' + data.message));
                }
                   
            })
            .catch(error => {
                console.error('Fout bij inloggen:', error);
                alert('Er is een fout opgetreden bij het inloggen.');
            });
    }
   
}

document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".btnLogin").forEach(x => x.addEventListener("click", loginBtnFunction));

});
function logoutBtnFunction(btn) {
    btn.preventDefault(); 
    var submit = btn.currentTarget;
    var action = submit.dataset.action;
    console.log("Logout button clicked");

    if (action === 'logout') {
        fetch('/v1/user/logout/', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json; charset=utf-8'
                },
                body: JSON.stringify({}),
                cache: 'default'
            })
                .then(response => {
                    console.log(response);
                 window.location.href= "/logout";
                })
                .catch(error => {
                    alert(error);
                });
}
}

document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".logoutBtn").forEach(x => x.addEventListener("click", logoutBtnFunction));
});

function showPage(data){

    fetch(`/v1/render/${data.user_type}`, {
        method: 'POST',
        dataType:'json',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json; charset=utf-8'
        },
        body: JSON.stringify({}),
        cache: 'default'
    })
    .then(response => response.json())
    .then(pageContent => {

        history.pushState({"content": pageContent}, "", `/${data.user_type}`);
       console.log(pageContent);
       document.body.innerHTML = pageContent;
       initializeModalEvents();
    })
    .catch(error => {
        console.error('Fout bij inloggen:', error);
        alert('Er is een fout opgetreden bij het inloggen.');
    });
}

function jsbuttonFunction(evt) {
 
        var submit = evt.currentTarget;
        var categoryId = submit.dataset.id;
        var action = submit.dataset.action;
    
if (action === 'delete') {
            var confirmDelete = confirm("Weet je zeker dat je categorie wilt verwijderen?");
            if (confirmDelete) {
                fetch('/v1/categorie/delete/' + categoryId, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({}),
                    cache: 'default'
                })
                    .then(response => {
                        console.log(response);
                        location.reload(true);
                    })
                    .catch(error => {
                        alert(error);
                    });
            }
        } else if (action === 'get') {
            fetch('/v1/categorie/get/' + categoryId, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                cache: 'default'
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // Check if the data is not empty before using it
                    if (data) {
                        var categorieNaam = document.getElementById('updateCategorie_naam');
                        categorieNaam.value = data.categorie_naam;
                        document.querySelector('.updateCategorieBtn').dataset.id = categoryId;
                    }
                })
                .catch(error => console.error('Error:', error));
    
        } else if (action === 'update') {
            var categorieNaam = document.getElementById('updateCategorie_naam').value;
            var updateData = { categorie_naam: categorieNaam };
            fetch('/v1/categorie/update/' + categoryId, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(updateData),
                cache: 'default'
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    console.log(response);
                    var successMessage = document.getElementById('successMessage');
                    successMessage.textContent = "Categorie met succes bijgewerkt!";
                    successMessage.style.display = 'block';
                    setTimeout(function () {
                        successMessage.style.display = 'none';
                    }, 30000);
                    // Consider removing reload and handle navigation more gracefully
                    location.reload(true);
                })
                .catch(error => {
                    handleErrorResponse(error) ;
                });
        }else if (action === 'create') {
            var categorieNaam = document.getElementById('categorie_naam').value; 
            var createData = { categorie_naam: categorieNaam }; 
     
            fetch('/v1/categorie/create', { 
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(createData), 
                cache: 'default'
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
           categorieNaam
                var successMessage = document.getElementById('successMessage');
                successMessage.textContent = "Nieuwe categorie succesvol toegevoegd!";
                successMessage.style.display = 'block';
                setTimeout(function () {
                    successMessage.style.display = 'none';
                }, 30000);
                
                window.location.href = '../categorie';
            })
            .catch(error => {
                   console.error('An error occurred:', error);
            });
        }
    }

   
    
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".addCategorieBtn").forEach(x => x.addEventListener("click", jsbuttonFunction));
    
        document.querySelectorAll(".deleteCategorieBtn").forEach(x => x.addEventListener("click", jsbuttonFunction));
    
        document.querySelectorAll(".getCategorieBtn").forEach(x => x.addEventListener("click", jsbuttonFunction));
    
        document.querySelectorAll(".updateCategorieBtn").forEach(x => x.addEventListener("click", jsbuttonFunction));
    });
    

// start update & delete js function Zaal 
function UpdateDeleteFunction(evt) {
    var submit = evt.currentTarget;
    var ZaalId = submit.dataset.id;
    var action = submit.dataset.action;

    if (action === 'delete') {
        var confirmDelete = confirm("Weet je zeker dat je zaal wilt verwijderen?");
        if (confirmDelete) {
            fetch('/v1/zaal/delete/' + ZaalId, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({}),
                cache: 'default'
            })
                .then(response => {
                    console.log(response);
                    window.location.href = '../zaal';
                })
                .catch(error => {
                    alert(error);
                });
        }
    } else if (action === 'get') {
        fetch('/v1/zaal/get/' + ZaalId, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            cache: 'default'
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Check if the data is not empty before using it
                if (data) {
                    var ZaalNaam = document.getElementById('updateZaal_naam');
                    ZaalNaam.value = data.zaal_naam;
                    document.querySelector('.updateZaalBtn').dataset.id=ZaalId;

                }
            })
            .catch(error => console.error('Error:', error));

    } else if (action === 'update') {
        var ZaalNaam = document.getElementById('updateZaal_naam').value;
        var updateData = { zaal_naam: ZaalNaam };
        fetch('/v1/zaal/update/' + ZaalId, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(updateData),
            cache: 'default'
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                console.log(response);
                var successMessage = document.getElementById('successMessage');
                successMessage.textContent = "Zaal Naam met succes bijgewerkt!";
                successMessage.style.display = 'block';
                setTimeout(function () {
                    successMessage.style.display = 'none';
                }, 30000);
                // Consider removing reload and handle navigation more gracefully
                location.reload(true);
            })
            .catch(error => {
                alert(error);
            });
    }
}
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".deleteZaalBtn").forEach(x => x.addEventListener("click", UpdateDeleteFunction));

    document.querySelectorAll(".getZaalBtn").forEach(x => x.addEventListener("click", UpdateDeleteFunction));

    document.querySelectorAll(".updateZaalBtn").forEach(x => x.addEventListener("click", UpdateDeleteFunction));
});

// select locatie and display the zaal_naam these belong to locatie
function updateZaalOptions(zalen) {
    var selectElement = document.getElementById("zaalNaam");
    
    // first make al option in select element empty 
    selectElement.innerHTML = '';

    var defaultOption = document.createElement('option');
    defaultOption.value = '';
    defaultOption.textContent = 'Selecteer een zaal';
    selectElement.appendChild(defaultOption);

    zalen.forEach(function (zaal) {
        var option = document.createElement('option');
        option.value = zaal.id;
        option.textContent = zaal.zaal_naam; // Toon de naam van de zaal
        selectElement.appendChild(option);
    });
}