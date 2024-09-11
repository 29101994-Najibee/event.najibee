document.addEventListener("DOMContentLoaded", initializeModalEvents);

function initializeModalEvents() {
    var modal = document.getElementById("myModal");
    var btns = document.querySelectorAll(".openModalBtn");
    var span = document.getElementsByClassName("close")[0];

    // Loop through each button and attach event listener
    btns.forEach(function(btn) {
      btn.onclick = function() {
        modal.style.display = "block";
      }
    });

    span.onclick = function() {
      modal.style.display = "none";
    }

    window.onclick = function(event) {
      if (event.target == modal) {
        modal.style.display = "none";
      }
    }
}

function presentatieFunction(event) {
    var submit = event.currentTarget;
    var presentatieId = submit.dataset.id;
    var action = submit.dataset.action;

    if (action === 'get') {
        fetch('/v1/presentatie/get/' + presentatieId, {
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
            if (data) {
                var updateSpreker_naam = document.getElementById('updateSpreker_naam');
                updateSpreker_naam.value = data.spreker_naam;
                var updateEvenement_naam = document.getElementById('updateEvenement_naam');
                updateEvenement_naam.value = data.evenement_id;
                var updateStart_tijd = document.getElementById('updateStart_tijd');
                updateStart_tijd.value = data.pr_start_tijd;
                var updateEind_tijd = document.getElementById('updateEind_tijd');
                updateEind_tijd.value = data.pr_eind_tijd;
                var updateBeschrijving = document.getElementById('updateBeschrijving');
                updateBeschrijving.value = data.pr_beschrijving;
             
                document.querySelector('.updatePresentatieBtn').dataset.id = presentatieId;  
            }
        })
        .catch(error => console.error('Error:', error));
    } else if (action === 'edit') {
        var updateSpreker_naam = document.getElementById('updateSpreker_naam').value;
        var updateEvenement_naam = document.getElementById('updateEvenement_naam').value;
        var updateStart_tijd = document.getElementById('updateStart_tijd').value;
        var updateEind_tijd = document.getElementById('updateEind_tijd').value;
        var updateBeschrijving = document.getElementById('updateBeschrijving').value;
        var updateData = {
            spreker_naam: updateSpreker_naam,
            evenement_naam: updateEvenement_naam,
            pr_start_tijd: updateStart_tijd,
            pr_eind_tijd: updateEind_tijd,
            pr_beschrijving: updateBeschrijving
        };
        fetch('/v1/presentatie/edit/' + presentatieId, {
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
                successMessage.textContent = "Presentatie met succes bijgewerkt!";
                successMessage.style.display = 'block';
                setTimeout(function () {
                    successMessage.style.display = 'none';
                }, 30000);
                // Consider removing reload and handle navigation more gracefully
                location.reload(true);
            })
            .catch(error => {
                handleErrorResponse(error);
            });
        
    } else if (action === 'delete') {
        var confirmDelete = confirm("Weet je zeker dat je deze presentatie wilt verwijderen?");
        if (confirmDelete) {
            fetch('/v1/presentatie/delete/' + presentatieId, {
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
    }
}


document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll(".getPresentatieBtn").forEach(x => x.addEventListener("click", presentatieFunction));

  document.querySelectorAll(".updatePresentatieBtn").forEach(x => x.addEventListener("click", presentatieFunction));

  document.querySelectorAll(".deletePresentatieBtn").forEach(x => x.addEventListener("click", presentatieFunction));
});

