function changeToEmptyStar()
{
    document.getElementById('fullStar').src = "../img/star_border-36px.png";
}
function changeToFullStar()
{
    document.getElementById('emptyStar').src="../img/full_star_36px.png";
}
function changePwdBorder()
{
    document.getElementById('pwd').style.borderColor="red";
    document.getElementById('pwdR').style.borderColor="red";
}
function changeEmailBorder()
{
    document.getElementById('email').style.borderColor="red";
}
function changeMatricolaBorder()
{
    document.getElementById('matricola').style.borderColor="red";
}
function jumpTo(hash)
{
    location.hash = "#"+hash;
}




