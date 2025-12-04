const observer = new IntersectionObserver((entries)=>{
  entries.forEach(entry=>{
    if(entry.isIntersecting){
      entry.target.classList.remove("opacity-0","translate-y-10");
    }
  });
});

document.querySelectorAll(".reveal").forEach((el)=>observer.observe(el));


let header = document.getElementById("header");
let title = document.querySelector(".title");
let menu = document.querySelectorAll(".menu ul li a");

function ubahWarnaHeader(){
    if (window.scrollY > 0){
         header.style.backgroundColor = "rgb(85, 192, 235)";
    header.style.borderBottom = "none";

    title.style.color = "white";
    menu.forEach(function(item){
        item.style.color = "white";
    });

    
    }else{
        header.style.backgroundColor = "transparent";
        header.style.borderBottom = "1px solid rgb(237, 236, 236)";

         title.style.color = "black";
    menu.forEach(function(item){
        item.style.color = "black";
    });
   
}
}
window.addEventListener("scroll", ubahWarnaHeader);

let floatingbutton = document.getElementById("floating-button");

function showOrHideFloatingbutton(){
    if(window.scrollY > 0){
        floatingbutton.style.display = "flex";
    }else{
        floatingbutton.style.display = "none";
     
    }
}
 

// const urlParams = new URLSearchParams(window.location.search);
// const product = urlParams.get('product');
// document.getElementById("productName").textContent = product;

