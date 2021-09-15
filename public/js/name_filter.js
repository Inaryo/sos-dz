let text_input = document.getElementById('name-filter')
let name = document.getElementsByClassName("name")
let row = document.getElementsByClassName("row")

text_input.addEventListener("input", (event) => {

    let text_content=event.target.value;
    let i;
    if (text_content === "") {
        for (i = 0; i < name.length; i += 1) {
            row[i].classList.remove('vanish')
        }
    } else {
        for (i=0; i < name.length; i += 1) {
            if(isEqual(text_content,name[i].textContent)){
                row[i].classList.remove("vanish")
            }
            else {
                row[i].classList.add("vanish")
            }

        }

    }


})

function isEqual(str1, str2) {
    return str1.toUpperCase() === str2.toUpperCase()
}