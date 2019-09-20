//表單資料確認
let loginF = document.getElementById('loginForm');
let registerF = document.getElementById('registerForm');

function loginInfoCheck() {
    let username = document.getElementById('username');
    let password = document.getElementById('password');
    let errorMessage = 0;

    if (username.value === "" || password.value === "") errorMessage = "請輸入登入資訊";

    errorMessage === 0 ? loginF.submit() : alert(errorMessage);
}

function registerInfoCheck() {
    let newName = document.getElementById('register_name');
    let newEmail = document.getElementById('register_email');
    let newUserName = document.getElementById('register_username');
    let newPassword = document.getElementById('register_password');
    let newDoublePassword = document.getElementById('register_password_check');

    let errorMessage = 0;

    if (newName.value === '' || newUserName.value === '' || newPassword.value === '' || newDoublePassword.value === '' || newEmail.value === '') errorMessage = "請輸入欄位";
    if (newPassword.length <= 6 && newDoublePassword.length <= 6) errorMessage = "密碼長度不可低於6字元";
    if (newPassword.value !== newDoublePassword.value) errorMessage = "密碼不相等";

    errorMessage === 0 ? registerF.submit() : alert(errorMessage);
}


//連動式選單
function linkSelectChange(arr) {
    let selectForm = document.getElementById('selectForm');
    let selectFormTwo = document.getElementById('selectCrop');

    let optionValue = '<option>請選擇農田</option>';
    let res = "";
    _.forEach(arr, function (optain) {
        res = _.split(optain, '_');
        if (res[0] === selectForm.value) {
            optionValue += `<option>${res[1]}</option>`
        }
    });

    selectFormTwo.innerHTML = optionValue;

}