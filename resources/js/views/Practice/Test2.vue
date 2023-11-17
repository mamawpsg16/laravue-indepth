<template>
    <div class="d-flex">
        <div class="row mx-auto">
            <img :src="selectedVariantDetails.image" class="text-center" style="width:200px; height:200px;">
            <!-- <p>{{ variantMaintenace }}</p>
            <p>User Count : {{ variantUserCount }}</p> -->
            <button type="button" class="form-control" @click.prevent="updateSaleStatus(selectedVariantDetails.id)">Sale Status {{
                selectedVariantDetails.onSale ? 'Sale' : 'Regular' }}</button>
            <ul>
                <li v-for="(variant, index) in variants" :key="variant.id" style="width:20%;"
                    @mouseover="updateVariant(index)">{{ variant.name
                    }}</li>
            </ul>
            <h4>Onsale Products</h4>
            <p v-for="variant in checkVariantSaleStatus" :key="variant.id">{{ variant.name }}</p>
            
        </div>
        <!-- <div>
            <p>{{ greetings }}</p>
            <input type="text" v-model="name">
            <button @click.prevent="count++">{{ count }} +</button>
        </div> -->
    </div>
</template>

<script>
import image1 from '../../../assets/images/1.png'
import image2 from '../../../assets/images/2.png'
import image3 from '../../../assets/images/3.png'
export default{
    data(){
        return{
            selectedVariant:0,
            variants:[
                {
                    id: 0,
                    name: 'Google',
                    image: image1,
                    user_count: 10,
                    onSale: true,
                    maintenance: false,
                },
                {
                    id: 1,
                    name: 'Google Workspace',
                    onSale: true,
                    image: image2,
                    user_count: 25,
                    maintenance: false,
                },
                {
                    id: 2,
                    name: 'Hangouts',
                    onSale: false,
                    image: image3,
                    user_count: 66,
                    maintenance: true,
                },
            ]
        }
    },
    computed:{
       selectedVariantDetails(){
            return this.variants.find(variant => variant.id === this.selectedVariant);
        },
        checkVariantSaleStatus(){
            let onSaleVariants = this.variants.filter(variant => variant.onSale === true);
            return onSaleVariants;
        }
    },
    methods:{
        updateSaleStatus(id){
            const index = this.variants.findIndex(variant => variant.id == id);
            console.log(this.variants[index].onSale);
            console.log(!this.variants[index].onSale);
            if(index != -1){
                this.variants[index].onSale = !this.variants[index].onSale;
            }
        },
        updateVariant(id){
            this.selectedVariant  = id;
        }
    }
}

// const dummyData = [{ name: 'Kevin', b: '', c: 1 }, { name: 'Kevin', b: 4, c: 2 }, { name: 'Rojenx', b: '', c: 2 }, { name: 'Kevin', b: 4, c: 2 }, { name: 'Rojenx', b: 4, c: 2 },];

// // checkDataForNullEmpty
// let nullData = new Set();
// dummyData.forEach(object => {
//     for (const key in object) {
//         let value = object[key];
//         if (value == null || value == '') {
//             nullData.add(key);
//         }
//     }
// })
// console.log(Array.from(nullData), 'NULL DATA');

// let initializeObject = {};
// // checkDuplicateCustomers
// dummyData.forEach(data => {
//     if (initializeObject[data.name]) {
//         initializeObject[data.name]++;
//     } else {
//         initializeObject[data.name] = 1;
//     }
// })
// let duplicateCustomers = Object.keys(initializeObject).filter(key => initializeObject[key] > 1);


// //remove a property
// let shallowdummyData = dummyData.slice();
// let removeProperty = shallowdummyData.map(data => {
//     delete data.name;
//     return data;
// });
// console.log(removeProperty, 'removeProperty');

// //add a property
// let addProperty = removeProperty.map(data => {
//     return { ...data, id: Math.trunc(Math.random() * 10 + 1) };
// });


// //Reverse a String:
// let reverse = 'kevin';
// let reversedWord = '';
// for (let i = reverse.length - 1; i >= 0; i--) {
//     reversedWord += reverse[i];
// }
// console.log(reversedWord, 'Reverse a String');


// //Palindrome Check
// function wordsForPalindromeTest(word) {
//     let reversedWord = '';
//     for (let i = word.trim().length - 1; i >= 0; i--) {
//         reversedWord += word[i];
//     }
//     if (reversedWord == word) {
//         return 'Palindrome';
//     }
//     return 'Not Palindrome';
// }
// console.log(wordsForPalindromeTest('repaper'), '!PALINDROME CHECK');

// //FizzBuzz
// // Write a function that prints numbers from 1 to 100. For multiples of 3, print "Fizz",
// //  for multiples of 5, print "Buzz", and for multiples of both 3 and 5, print "FizzBuzz".
// let fizzBuzzArray = [];
// function fizzBuzz(number) {
//     for (let i = 1; i < number; i++) {
//         if (i % 3 == 0 && i % 5 == 0) {
//             fizzBuzzArray.push("FizzBuzz");
//         } else if (i % 3 == 0) {
//             fizzBuzzArray.push("Fizz");
//         } else if (i % 5 == 0) {
//             fizzBuzzArray.push("Buzz");
//         } else {
//             fizzBuzzArray.push(i);
//         }
//     }
// }
// // console.log(fizzBuzz(100),fizzBuzzArray,' FIZZ BUZZ');

// //LOOK FOR THE LONGEST WORD
// const sentence = "This is a test sentence to find the longest word.";

// //REDUCE
// // let longestWord = sentence.split(" ").reduce((prevWord,currWord) => prevWord.length > currWord.length ? prevWord : currWord ,'');

// //FOR LOOP
// let longestWord = '';
// let splitSentence = sentence.split(" ");
// for (let i = 0; i < splitSentence.length - 1; i++) {
//     if (splitSentence[i].length > longestWord.length) {
//         longestWord = splitSentence[i]
//     }
// }
// console.log(longestWord, 'LONGEST WORD')

// // ANAGRAM
// const word1 = "listen";
// const word2 = "silent";
// function anagram(word1, word2) {
//     return word1.toLowerCase().split('').sort().join('') == word2.toLowerCase().split('').sort().join('') ? 'Anagram' : 'Not Anagram';
// }
// console.log(anagram(word1, word2), 'ANAGRAM');

// //ROTATE ARRAY
// let numbers = [1, 2, 3, 4, 5];
// function rotateArray(array, position) {
//     const arrayShallowCopy = [...array];
//     for (let index = 0; index < position; index++) {
//         let element = arrayShallowCopy.pop();
//         arrayShallowCopy.unshift(element);
//     }
//     return arrayShallowCopy;

// }
// console.log(rotateArray(numbers, 2), 'ROTATE ARRAY');


// /** COUNT VOWELS */
// let vowels = ['a', 'e', 'i', 'o', 'u'];
// let vowel_count = 0;
// function countVowels(sentence) {
//     let trimmed_sentence = sentence.replace(/\s/g, "").toLowerCase();
//     for (let index = 0; index < trimmed_sentence.length; index++) {
//         if (vowels.includes(trimmed_sentence[index])) {
//             vowel_count++;
//         };
//     }
// }
// countVowels('hello AWFWFEworld')
// console.log(vowel_count, 'COUNT VOWELS');

// /** FIND MAX VALUE */
// let numbersArray = [1, 242, 2, 3, 4, 5, 6, 7, 8];
// // console.log(Math.max(...numbersArray),'MAX');
// let max = 0;
// function checkMaxInteger(array) {
//     for (let index = 0; index < numbersArray.length; index++) {
//         const element = array[index];
//         if (element > max) {
//             max = element;
//         }
//     }
// }
// checkMaxInteger(numbersArray)
// console.log(max, 'MAX');


// // Remove Duplicates:
// let withDuplicates = [1, 2, 2, 3, 4, 4, 5];
// console.log([...new Set([...withDuplicates])], 'REMOVE DUPLICATE');


// /** Title Case: */
// let titleCase = 'this is a test';
// let updatedTitleCase = titleCase.split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');

// /**Reverse Array: */
// let reverseNumArray = [1, 2, 3, 4];
// let reverseNums = [];
// console.log(reverseNumArray.length, 'LKEAWS')
// for (let index = reverseNumArray.length - 1; index >= 0; index--) {
//     reverseNums.push(reverseNumArray[index]);
// }
// console.log(reverseNums, 'reverse 1');
// console.log(reverseNumArray.reverse(), 'REVERSE');

// /** FIND MISSING NUMBER */
// let findMissingNumber = [1, 2, 4, 5, 8];
// let missingNumber = [];
// for (let index = 0; index < findMissingNumber.length - 1; index++) {
//     const currentElement = findMissingNumber[index];
//     const nextElement = findMissingNumber[index + 1];

//     const diff = nextElement - currentElement;
//     if (diff > 1) {
//         for (let index = 1; index < diff; index++) {
//             missingNumber.push(currentElement + index);
//         }
//     }

// }
// console.log(missingNumber, 'MISSING NUMBER  ');


// function removeSpecialCharsAndSpaces(inputString) {
//     // Remove special characters using regular expression
//     const stringWithoutSpecialChars = inputString.replace(/[^\w\s]/gi, '');

//     // Remove spaces from the string
//     const finalString = stringWithoutSpecialChars.replace(/\s+/g, '');

//     return finalString;
// }

// /** PALINDROME ADVANCE */
// let sentenceToCheck = "A man, a plan, a canal, Panamsa!";
// // console.log(removeSpecialCharsAndSpaces(sentenceToCheck));
// function palindromeAdvance(sentence) {
//     let sanitizeSentence = removeSpecialCharsAndSpaces(sentence).toLowerCase();
//     let reverseSentence = '';
//     for (let index = sanitizeSentence.length - 1; index >= 0; index--) {
//         const element = sanitizeSentence[index];
//         reverseSentence += element;
//     }
//     if (sanitizeSentence == reverseSentence) {
//         return 'Palindrome'
//     }
//     return 'Not Palindrome';

// }
// console.log(palindromeAdvance(sentenceToCheck), 'ADVANCE PALINDROME');

// /** CHECK IF UNIQUE */
// let stringIfUnique = "AbcdEFC";
// function isUnique(string) {
//     let stringtoLower = string.toLowerCase();
//     let Charactercounter = {};
//     for (let i = 0; i < string.length; i++) {
//         if (Charactercounter[stringtoLower[i]]) {
//             Charactercounter[stringtoLower[i]]++;
//         } else {
//             Charactercounter[stringtoLower[i]] = 1;
//         }
//     }
//     let notUnique = [];
//     for (let [key, value] of Object.entries(Charactercounter)) {
//         if (value > 1) {
//             notUnique.push(value);
//         }
//     }
//     return notUnique.length >= 1 ? 'Not Unique' : 'Unique';
// }

// /** SUM OF TWO */
// const foundTargetNumbers = [2, 4, 11, 15];
// const target = 15;
// function findTheSumOfTwo(numbers, target) {
//     for (let i = 0; i <= numbers.length - 1; i++) {
//         for (let j = i + 1; j < numbers.length; j++) {
//             const currentElement = numbers[i];
//             const nextElement = numbers[j];
//             const sum = currentElement + nextElement;
//             if (sum == target) {
//                 return [currentElement, nextElement];
//             }
//         }
//     }
//     return null;
// }

// console.log(findTheSumOfTwo(foundTargetNumbers, target));


// /** EVEN OR ODD */
// function isEvenOrOdd(number) {
//     return number % 2 == 0 ? 'Even' : 'Odd';
// }
// console.log(isEvenOrOdd(2), 'isEvenOrOdd');

// /** Longest Substring Without Repeating Characters */
// const subString = 'abcabcbb';
// function CheckSubstring(subString) {
//     let stringCounter = {};
//     let withoutRepeatingCharacters = '';
//     for (let index = 0; index < subString.length; index++) {
//         if (!stringCounter[subString[index]]) {
//             stringCounter[subString[index]] = 1;
//             withoutRepeatingCharacters += subString[index];
//         } else {
//             return withoutRepeatingCharacters;
//         }
//     }
//     return subString;
// }

// console.log(CheckSubstring(subString));


// /** LEET CODE CODING CHALLENGE */
// const nums = Array.from({ length: 10000 }, (_, i) => i + 1);
// let targetNum = 6;
// function twoSumB(nums, target) {
//     let left = 0;
//     let right = nums.length - 1;
//     while (left < right) {
//         let sum = nums[left] + nums[right];
//         if (sum == target) {
//             return [left, right];
//         } else if (sum < target) {
//             left++;
//         } else {
//             right--;
//         }
//     }
// };
// console.log(twoSumB(nums, targetNum), 'WOW');


// /** FIZZBUZZ CDC */
// function fizzBuzz1(numRange) {
//     for (let i = 1; i <= numRange ; i++) {
//         if (i % 3 == 0 && i % 5 == 0) {
//             console.log('FizzBuzz');
//         }else if (i % 3 == 0) {
//             console.log('Fizz');
//         } else if (i % 5 == 0) {
//             console.log('Buzz');
//         }else{
//             console.log(i);
//         }
//     }
// }
// // fizzBuzz1(100);

// /** uniqueInOrder('AAAABBBCCDAABBB') */
// // uniqueInOrder('AAAABBBCCDAABBB') == ['A', 'B', 'C', 'D', 'A', 'B']
// // uniqueInOrder('ABBCcAD')         == ['A', 'B', 'C', 'c', 'A', 'D']
// // uniqueInOrder([1,2,2,3,3])       == [1,2,3]
// function uniqueInOrder(iterable) {
//     let uniqueString =[];
//    for (let i = 0; i <= iterable.length -1; i++) {
//         let currentElement = iterable[i]; 
//         console.log(currentElement,'current');
//         let nextElement = iterable[i + 1]; 
//         console.log(nextElement,'next');
//         console.log(currentElement == nextElement);
//         if(currentElement == nextElement){
//             continue;
//         }
//         uniqueString.push(currentElement);
//     }
//     return uniqueString;
// }

// console.log(uniqueInOrder([1,2,2,3,3]),'SHIT');
</script>

<style lang="scss" scoped></style>