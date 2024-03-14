// Zad1
function pifag(a, b, c) {
   var a2 = a * a;
   var b2 = b * b;
   var c2 = c * c;

   if (a + b <= c || a + c <= b || b + c <= a) {
      return false;
   }
   return a2 + b2 === c2 || a2 + c2 === b2 || b2 + c2 === a2;
}
// Zad2
function div(a, b, c) {
   for (var i = a; i <= b; i++) {
      if (i % c === 0) {
         console.log(i);
      }
   }
}
// Zad3
function mult(bok) {
   console.log("Wynik mnożenia przez " + bok);
   for (var i = 1; i <= bok; i++) {
      var wiersz = "";
      for (var j = 1; j <= bok; j++) {
         var wynik = i * j;
         wiersz += wynik + " ";
      }
      console.log(wiersz.trim());
   }
}
// Zad4
function fib(ciag) {
   var a = 0, b = 1, wynik;
   console.log(a);
   console.log(b);
   for (var i = 2; i < ciag; i++) {
      wynik = a + b;
      console.log(wynik);
      a = b;
      b = wynik;
   }
}
// Яфв5
function chobibi(chobibi) {
   for (var i = 1; i <= chobibi; i++) {
      var gwia = "";
      for (var j = 1; j <= i; j++) {
         gwia += "*";
      }
      console.log(gwia);
   }
}
// Zad7
function opf(typ, a, b, h) {
   let pole = 0;
   switch (typ) {
      case "prostokat":
         pole = poleProstokata(a, b);
         break;
      case "trapez":
         pole = poleTrapezu(a, b, h);
         break;
      case "równoleglobok":
         pole = poleRownolegloboka(a, h);
         break;
      case "trojkat":
         pole = poleTrojkata(a, h);
         break;
      default:
         console.log("WTF?");
   }
   return pole;
}

function poleProstokata(a, b) {
   console.log(a * b);
}

function poleTrapezu(a, b, h) {
   console.log(((a + b) / 2) * h);
}

function poleRownolegloboka(a, h) {
   console.log(a * h);
}

function poleTrojkata(a, h) {
   console.log((a * h) / 2);
}

// Zad8
const prostokat = (a, b) => a * b;
const trapez = (a, b, h) => ((a + b) / 2) * h;
const rownoleglobok = (a, h) => a * h;
const trojkat = (a, h) => (a * h) / 2;
const ppole = (figura, wymiary, obliczPole) => {
   console.log(` Pole ${figura}u  wynosi: ${obliczPole(...wymiary)}`);
};

// Zad9
function pascal(h) {
   let trojkat = [];
   for (let i = 0; i < h; i++) {
      trojkat[i] = [];
      for (let j = 0; j <= i; j++) {
         if (j === 0 || j === i) {
            trojkat[i][j] = 1;
         } else {
            trojkat[i][j] = trojkat[i - 1][j - 1] + trojkat[i - 1][j];
         }
      }
   }
   for (let i = 0; i < h; i++) {
      let wierz = "";
      for (let j = 0; j <= i; j++) {
         wierz += trojkat[i][j] + " ";
      }
      console.log(wierz);
   }
}

// zad10
function kgb(niedozwolone, zdanie) {
   let slowa = zdanie.split(' ');
   for (let i = 0; i < slowa.length; i++) {
      if (niedozwolone.includes(slowa[i])) {
         slowa[i] = '*'.repeat(slowa[i].length);
      }
   }

   console.log(slowa.join(' '));
}
// zad 6
// function habibi(max) {
//    var i = 0,
//       j = 0;
//    var space = "",
//       star = "";

//    while (i < max) {
//       space = "";
//       star = "";
//       for (j = 0; j < max - i; j++) space += "*";
//       for (j = 0; j < 2 * i + 1; j++) star += " ";
//       console.log(space + star);
//       i++;
//    }
// }
