export function initPrefillDataDiri() {
    const box = document.getElementById('prefillData');
    if (!box) return;

    const namaUser   = box.dataset.nama || '';
    const phoneUser  = box.dataset.phone || '';
    const genderUser = box.dataset.gender || '';
    const emailUser  = box.dataset.email || '';

    const namaPas  = box.dataset.namaPasangan || '';
    const phonePas = box.dataset.phonePasangan || '';

    const namaCpp  = document.getElementById('nama_cpp');
    const phoneCpp = document.getElementById('phone_cpp');
    const emailCpp = document.getElementById('email_cpp');

    const namaCpw  = document.getElementById('nama_cpw');
    const phoneCpw = document.getElementById('phone_cpw');
    const emailCpw = document.getElementById('email_cpw');

    // Perempuan → CPW, Laki-laki → CPP
    if (genderUser === 'perempuan') {
        // USER = CPW
        if (namaCpw)  namaCpw.value  = namaUser;
        if (phoneCpw) phoneCpw.value = phoneUser;
        if (emailCpw) emailCpw.value = emailUser;

        // PASANGAN = CPP
        if (namaCpp)  namaCpp.value  = namaPas;
        if (phoneCpp) phoneCpp.value = phonePas;
    } else {
        // USER = CPP
        if (namaCpp)  namaCpp.value  = namaUser;
        if (phoneCpp) phoneCpp.value = phoneUser;
        if (emailCpp) emailCpp.value = emailUser;

        // PASANGAN = CPW
        if (namaCpw)  namaCpw.value  = namaPas;
        if (phoneCpw) phoneCpw.value = phonePas;
    }
}

