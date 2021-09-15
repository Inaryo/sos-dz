slides = document.getElementsByClassName("slide");
radio = document.getElementsByClassName("radio")

r1 = document.getElementById("r1")
r2 = document.getElementById("r2")
r3 = document.getElementById("r3")
r4 = document.getElementById("r4")

// r1.addEventListener("change", () => {
//     for (let i = 0; i < radio.length; i += 1) {
//         slides[i].classList.add("vanish")
//         radio[i].classList.remove("change_color")
//         if (i == 0)
//
//             slides[i].classList.remove("vanish")
//     }
// })
// r2.addEventListener("change", () => {
//     for (let i = 0; i < radio.length; i += 1) {
//         slides[i].classList.add("vanish")
//
//         if (i == 1)
//
//             slides[i].classList.remove("vanish")
//     }
// })
//
// r3.addEventListener("change", () => {
//     for (let i = 0; i < radio.length; i += 1) {
//         slides[i].classList.add("vanish")
//
//         if (i == 2)
//
//             slides[i].classList.remove("vanish")
//     }
// })
// r4.addEventListener("change", () => {
//     for (let i = 0; i < radio.length; i += 1) {
//         slides[i].classList.add("vanish")
//
//         if (i == 3)
//
//             slides[i].classList.remove("vanish")
//     }
// })

r1.addEventListener("change", () => {
    for (let i = 0; i < radio.length; i += 1) {
        slides[i].classList.add("vanish");
        radio[i].classList.remove("change_color");
        if (i == 0){
            radio[i].classList.add("change_color");
            slides[i].classList.remove("vanish");
        }

    }
})
r2.addEventListener("change", () => {
    for (let i = 0; i < radio.length; i += 1) {
        slides[i].classList.add("vanish")
        radio[i].classList.remove("change_color")
        if (i == 1)
        {
            radio[i].classList.add("change_color")
            slides[i].classList.remove("vanish")
        }

    }
})

r3.addEventListener("change", () => {
    for (let i = 0; i < radio.length; i += 1) {
        slides[i].classList.add("vanish")
        radio[i].classList.remove("change_color")
        if (i == 2){
            radio[i].classList.add("change_color")
            slides[i].classList.remove("vanish")
        }

    }
})
r4.addEventListener("change", () => {
    for (let i = 0; i < radio.length; i += 1) {
        slides[i].classList.add("vanish")
        radio[i].classList.remove("change_color")
        if (i == 3){
            radio[i].classList.add("change_color")
            slides[i].classList.remove("vanish")
        }

    }
})


