window.onload = () => {

    let boutons = document.querySelectorAll(".custom-control-input");

    boutons.forEach(function (button) {
        button.addEventListener("click", actif);
    });

    function actif() {
        let xmlhttp = new XMLHttpRequest;

        ////Récupère m'attribut grace à l'attribut data-id="<?= $annonce->id ?>
        xmlhttp.open('GET', '/admin/activeAnnonce/' + this.dataset.id)
        xmlhttp.send()
    }

}