/**
 * BeeStyle E-Commerce Validation Engine & Schemas (ES Module / Vite compatible)
 */

export const SUPPORTED_BANKS = {
    'VCB': 'Vietcombank - Ngân hàng TMCP Ngoại Thương Việt Nam',
    'CTG': 'VietinBank - Ngân hàng TMCP Công Thương Việt Nam',
    'BIDV': 'BIDV - Ngân hàng TMCP Đầu tư và Phát triển Việt Nam',
    'VBA': 'Agribank - Ngân hàng Nông nghiệp & Phát triển Nông thôn',
    'TCB': 'Techcombank - Ngân hàng TMCP Kỹ Thương Việt Nam',
    'MB': 'MBBank - Ngân hàng TMCP Quân Đội',
    'ACB': 'ACB - Ngân hàng TMCP Á Châu',
    'VPB': 'VPBank - Ngân hàng TMCP Việt Nam Thịnh Vượng',
    'TPB': 'TPBank - Ngân hàng TMCP Tiên Phong',
    'STB': 'Sacombank - Ngân hàng TMCP Sài Gòn Thương Tín',
    'HDB': 'HDBank - Ngân hàng TMCP Phát triển TP.HCM',
    'VIB': 'VIB - Ngân hàng TMCP Quốc tế Việt Nam',
    'MSB': 'MSB - Ngân hàng TMCP Hàng Hải Việt Nam',
    'OCB': 'OCB - Ngân hàng TMCP Phương Đông',
    'SHB': 'SHB - Ngân hàng TMCP Sài Gòn - Hà Nội',
    'LPB': 'LPBank - Ngân hàng TMCP Lộc Phát Việt Nam',
    'SSB': 'SeABank - Ngân hàng TMCP Đông Nam Á',
    'BAB': 'BacABank - Ngân hàng TMCP Bắc Á',
    'EIB': 'Eximbank - Ngân hàng TMCP Xuất Nhập Khẩu Việt Nam',
    'PVC': 'PVcomBank - Ngân hàng TMCP Đại Chúng Việt Nam',
    'KLB': 'Kienlongbank - Ngân hàng TMCP Kiên Long',
    'NAB': 'Nam A Bank - Ngân hàng TMCP Nam Á',
    'BVB': 'BaoVietBank - Ngân hàng TMCP Bảo Việt',
    'VBB': 'VietBank - Ngân hàng TMCP Việt Nam Thương Tín',
    'SCB': 'SCB - Ngân hàng TMCP Sài Gòn',
    'PGB': 'PGBank - Ngân hàng TMCP Thịnh vượng và Phát triển',
    'WOO': 'Woori Bank Việt Nam',
    'UOB': 'UOB - United Overseas Bank',
    'HSVN': 'HSBC Việt Nam',
    'SCVN': 'Standard Chartered Bank Việt Nam',
    'CAKE': 'CAKE by VPBank',
    'TIMO': 'Timo by BanViet',
};

export const REGEX_PATTERNS = {
    VN_PHONE: /^(?:\+?84|0)(?:3[2-9]|5[25689]|7[06-9]|8[1-9]|9[0-9])[0-9]{7}$/,
    EMAIL: /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/,
    HAS_UPPERCASE: /[A-Z]/,
    HAS_LOWERCASE: /[a-z]/,
    HAS_NUMBER: /[0-9]/,
    HAS_SPECIAL: /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?~`]/,
    UPPERCASE_NO_ACCENT: /^[A-Z\s]+$/,
    BANK_ACCOUNT_NUMBER: /^[0-9A-Za-z]{6,20}$/,
    IMAGE_EXTENSIONS: /\.(jpg|jpeg|png|webp)$/i,
};

export const Utils = {
    trim(str) {
        return typeof str === 'string' ? str.trim() : (str ?? '');
    },
    normalizeSpaces(str) {
        return typeof str === 'string' ? str.trim().replace(/\s+/g, ' ') : '';
    },
    cleanPhone(phone) {
        if (!phone) return '';
        let p = String(phone).replace(/[\s\-\.\(\)]+/g, '');
        if (p.startsWith('+84')) {
            p = '0' + p.substring(3);
        } else if (p.startsWith('84') && p.length === 11) {
            p = '0' + p.substring(2);
        }
        return p;
    },
    isNumeric(val) {
        return !isNaN(parseFloat(val)) && isFinite(val) && Number(val) > 0;
    },
    isBeforeToday(dateStr) {
        if (!dateStr) return true;
        const inputDate = new Date(dateStr);
        if (isNaN(inputDate.getTime())) return false;
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        return inputDate < today;
    }
};

export function validateRegister(data = {}) {
    const errors = {};
    const cleaned = {
        name: Utils.normalizeSpaces(data.name),
        email: Utils.trim(data.email).toLowerCase(),
        phone: Utils.cleanPhone(data.phone),
        password: data.password || '',
        password_confirmation: data.password_confirmation ?? data.confirm_password ?? '',
        terms: Boolean(data.terms),
    };

    if (!cleaned.name) {
        errors.name = 'Vui lòng nhập họ và tên của bạn.';
    } else if (cleaned.name.length < 2) {
        errors.name = 'Họ và tên phải có tối thiểu 2 ký tự.';
    } else if (cleaned.name.length > 50) {
        errors.name = 'Họ và tên không được vượt quá 50 ký tự.';
    }

    const hasEmail = Boolean(cleaned.email);
    const hasPhone = Boolean(cleaned.phone);

    if (!hasEmail && !hasPhone) {
        errors.email = 'Vui lòng nhập địa chỉ Email hoặc Số điện thoại để tạo tài khoản.';
    } else {
        if (hasEmail) {
            if (!REGEX_PATTERNS.EMAIL.test(cleaned.email)) {
                errors.email = 'Địa chỉ Email không đúng định dạng chuẩn (ví dụ: user@example.com).';
            } else if (cleaned.email.length > 255) {
                errors.email = 'Địa chỉ Email không được dài quá 255 ký tự.';
            }
        }
        if (hasPhone) {
            if (!REGEX_PATTERNS.VN_PHONE.test(cleaned.phone)) {
                errors.phone = 'Số điện thoại không đúng định dạng số Việt Nam 10 chữ số (bắt đầu bằng 03, 05, 07, 08, 09).';
            }
        }
    }

    if (!cleaned.password) {
        errors.password = 'Vui lòng nhập mật khẩu bảo vệ tài khoản.';
    } else if (cleaned.password.length < 8) {
        errors.password = 'Mật khẩu phải có tối thiểu 8 ký tự.';
    } else if (!REGEX_PATTERNS.HAS_UPPERCASE.test(cleaned.password)) {
        errors.password = 'Mật khẩu phải chứa ít nhất 1 chữ cái in hoa (A-Z).';
    } else if (!REGEX_PATTERNS.HAS_LOWERCASE.test(cleaned.password)) {
        errors.password = 'Mật khẩu phải chứa ít nhất 1 chữ cái in thường (a-z).';
    } else if (!REGEX_PATTERNS.HAS_NUMBER.test(cleaned.password)) {
        errors.password = 'Mật khẩu phải chứa ít nhất 1 chữ số (0-9).';
    } else if (!REGEX_PATTERNS.HAS_SPECIAL.test(cleaned.password)) {
        errors.password = 'Mật khẩu phải chứa ít nhất 1 ký tự đặc biệt (!@#$%^&*...).';
    }

    if (!cleaned.password_confirmation) {
        errors.password_confirmation = 'Vui lòng nhập lại mật khẩu để xác nhận.';
    } else if (cleaned.password !== cleaned.password_confirmation) {
        errors.password_confirmation = 'Mật khẩu xác nhận không khớp với mật khẩu đã nhập.';
    }

    if (!cleaned.terms) {
        errors.terms = 'Bạn phải đồng ý với Điều khoản và Quy định của BeeStyle để tiếp tục.';
    }

    return {
        isValid: Object.keys(errors).length === 0,
        errors,
        cleanedData: cleaned,
    };
}

export function validateLogin(data = {}) {
    const errors = {};
    const cleaned = {
        login_id: Utils.trim(data.login_id),
        password: data.password || '',
        remember: Boolean(data.remember),
    };

    if (!cleaned.login_id) {
        errors.login_id = 'Vui lòng nhập Email hoặc Số điện thoại để đăng nhập.';
    }

    if (!cleaned.password) {
        errors.password = 'Vui lòng nhập mật khẩu tài khoản.';
    }

    return {
        isValid: Object.keys(errors).length === 0,
        errors,
        cleanedData: cleaned,
    };
}

export function validateUpdateProfile(data = {}, files = {}) {
    const errors = {};
    const cleaned = {
        name: Utils.normalizeSpaces(data.name),
        email: Utils.trim(data.email).toLowerCase(),
        phone: Utils.cleanPhone(data.phone),
        gender: Utils.trim(data.gender).toLowerCase(),
        dob: Utils.trim(data.dob),
        address: Utils.normalizeSpaces(data.address),
        city: Utils.trim(data.city),
        district: Utils.trim(data.district),
    };

    const genderMap = {
        'nam': 'male',
        'nữ': 'female',
        'nu': 'female',
        'khác': 'other',
        'khac': 'other',
        'male': 'male',
        'female': 'female',
        'other': 'other',
    };
    if (cleaned.gender && genderMap[cleaned.gender]) {
        cleaned.gender = genderMap[cleaned.gender];
    }

    if (!cleaned.name) {
        errors.name = 'Vui lòng nhập họ và tên của bạn.';
    } else if (cleaned.name.length < 2) {
        errors.name = 'Họ và tên phải có tối thiểu 2 ký tự.';
    } else if (cleaned.name.length > 100) {
        errors.name = 'Họ và tên không được vượt quá 100 ký tự.';
    }

    if (!cleaned.email) {
        errors.email = 'Vui lòng nhập địa chỉ Email liên hệ.';
    } else if (!REGEX_PATTERNS.EMAIL.test(cleaned.email)) {
        errors.email = 'Địa chỉ Email không đúng định dạng chuẩn (ví dụ: user@example.com).';
    } else if (cleaned.email.length > 255) {
        errors.email = 'Địa chỉ Email không được vượt quá 255 ký tự.';
    }

    if (!cleaned.phone) {
        errors.phone = 'Vui lòng nhập số điện thoại liên lạc.';
    } else if (!REGEX_PATTERNS.VN_PHONE.test(cleaned.phone)) {
        errors.phone = 'Số điện thoại không đúng định dạng số Việt Nam 10 chữ số (bắt đầu bằng 03, 05, 07, 08, 09).';
    }

    const validGenders = ['male', 'female', 'other'];
    if (cleaned.gender && !validGenders.includes(cleaned.gender)) {
        errors.gender = 'Giới tính không hợp lệ. Vui lòng chọn Nam, Nữ hoặc Khác.';
    }

    if (cleaned.dob) {
        const dobDate = new Date(cleaned.dob);
        if (isNaN(dobDate.getTime())) {
            errors.dob = 'Ngày sinh không đúng định dạng ngày tháng hợp lệ.';
        } else if (!Utils.isBeforeToday(cleaned.dob)) {
            errors.dob = 'Ngày sinh phải là một ngày trong quá khứ (trước ngày hôm nay).';
        }
    }

    const avatarFile = files.avatar || (data.avatar instanceof File ? data.avatar : null);
    if (avatarFile) {
        const validMimes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
        const maxBytes = 2 * 1024 * 1024; // 2MB

        if (!validMimes.includes(avatarFile.type) && !REGEX_PATTERNS.IMAGE_EXTENSIONS.test(avatarFile.name)) {
            errors.avatar = 'Ảnh đại diện chỉ chấp nhận các định dạng ảnh: JPG, JPEG, PNG, WEBP.';
        } else if (avatarFile.size > maxBytes) {
            errors.avatar = 'Dung lượng ảnh đại diện không được vượt quá 2MB (2048 KB).';
        }
    }

    return {
        isValid: Object.keys(errors).length === 0,
        errors,
        cleanedData: cleaned,
    };
}

export function validateChangePassword(data = {}) {
    const errors = {};
    const cleaned = {
        current_password: data.current_password || '',
        new_password: data.new_password || '',
        confirm_new_password: data.confirm_new_password ?? data.new_password_confirmation ?? '',
    };

    if (!cleaned.current_password) {
        errors.current_password = 'Vui lòng nhập mật khẩu hiện tại của bạn.';
    }

    if (!cleaned.new_password) {
        errors.new_password = 'Vui lòng nhập mật khẩu mới cần thay đổi.';
    } else if (cleaned.new_password.length < 8) {
        errors.new_password = 'Mật khẩu mới phải có tối thiểu 8 ký tự.';
    } else if (!REGEX_PATTERNS.HAS_UPPERCASE.test(cleaned.new_password)) {
        errors.new_password = 'Mật khẩu mới phải chứa ít nhất 1 chữ cái in hoa (A-Z).';
    } else if (!REGEX_PATTERNS.HAS_LOWERCASE.test(cleaned.new_password)) {
        errors.new_password = 'Mật khẩu mới phải chứa ít nhất 1 chữ cái in thường (a-z).';
    } else if (!REGEX_PATTERNS.HAS_NUMBER.test(cleaned.new_password)) {
        errors.new_password = 'Mật khẩu mới phải chứa ít nhất 1 chữ số (0-9).';
    } else if (!REGEX_PATTERNS.HAS_SPECIAL.test(cleaned.new_password)) {
        errors.new_password = 'Mật khẩu mới phải chứa ít nhất 1 ký tự đặc biệt (!@#$%^&*...).';
    } else if (cleaned.current_password && cleaned.new_password === cleaned.current_password) {
        errors.new_password = 'Mật khẩu mới không được trùng với mật khẩu hiện tại.';
    }

    if (!cleaned.confirm_new_password) {
        errors.confirm_new_password = 'Vui lòng nhập lại mật khẩu mới để xác nhận.';
    } else if (cleaned.new_password !== cleaned.confirm_new_password) {
        errors.confirm_new_password = 'Xác nhận mật khẩu mới không khớp với mật khẩu mới đã nhập.';
    }

    return {
        isValid: Object.keys(errors).length === 0,
        errors,
        cleanedData: cleaned,
    };
}

export function validateBankAccount(data = {}) {
    const errors = {};
    const cleaned = {
        bank_code: Utils.trim(data.bank_code).toUpperCase(),
        account_number: Utils.trim(data.account_number).replace(/\s+/g, ''),
        account_holder_name: Utils.normalizeSpaces(data.account_holder_name).toUpperCase(),
        bank_branch: Utils.normalizeSpaces(data.bank_branch),
    };

    if (!cleaned.bank_code) {
        errors.bank_code = 'Vui lòng chọn ngân hàng thụ hưởng.';
    } else if (!SUPPORTED_BANKS[cleaned.bank_code]) {
        errors.bank_code = 'Ngân hàng được chọn không nằm trong danh sách hỗ trợ của hệ thống.';
    }

    if (!cleaned.account_number) {
        errors.account_number = 'Vui lòng nhập số tài khoản ngân hàng.';
    } else if (!REGEX_PATTERNS.BANK_ACCOUNT_NUMBER.test(cleaned.account_number)) {
        errors.account_number = 'Số tài khoản ngân hàng phải có từ 6 đến 20 ký tự (chỉ bao gồm số hoặc chữ cái, không chứa khoảng trắng hay ký tự đặc biệt).';
    }

    if (!cleaned.account_holder_name) {
        errors.account_holder_name = 'Vui lòng nhập họ tên chủ tài khoản ngân hàng.';
    } else if (cleaned.account_holder_name.length < 2) {
        errors.account_holder_name = 'Tên chủ tài khoản phải có tối thiểu 2 ký tự.';
    } else if (cleaned.account_holder_name.length > 100) {
        errors.account_holder_name = 'Tên chủ tài khoản không được vượt quá 100 ký tự.';
    } else if (!REGEX_PATTERNS.UPPERCASE_NO_ACCENT.test(cleaned.account_holder_name)) {
        errors.account_holder_name = 'Tên chủ tài khoản phải là chữ IN HOA KHÔNG DẤU, không chứa số hoặc ký tự đặc biệt (ví dụ: NGUYEN VAN A).';
    }

    return {
        isValid: Object.keys(errors).length === 0,
        errors,
        cleanedData: cleaned,
    };
}

export function validateShippingAddress(data = {}) {
    const errors = {};
    const cleaned = {
        receiver_name: Utils.normalizeSpaces(data.receiver_name ?? data.recipient_name),
        receiver_phone: Utils.cleanPhone(data.receiver_phone ?? data.phone),
        province_id: data.province_id,
        district_id: data.district_id,
        ward_id: data.ward_id,
        detailed_address: Utils.normalizeSpaces(data.detailed_address ?? data.address),
        is_default: Boolean(data.is_default),
        label: Utils.trim(data.label) || 'Nhà riêng',
        notes: Utils.trim(data.notes),
    };

    if (!cleaned.receiver_name) {
        errors.receiver_name = 'Vui lòng nhập họ và tên người nhận hàng.';
    } else if (cleaned.receiver_name.length < 2) {
        errors.receiver_name = 'Tên người nhận phải có ít nhất 2 ký tự.';
    } else if (cleaned.receiver_name.length > 50) {
        errors.receiver_name = 'Tên người nhận không được vượt quá 50 ký tự.';
    }

    if (!cleaned.receiver_phone) {
        errors.receiver_phone = 'Vui lòng nhập số điện thoại người nhận hàng.';
    } else if (!REGEX_PATTERNS.VN_PHONE.test(cleaned.receiver_phone)) {
        errors.receiver_phone = 'Số điện thoại người nhận phải là số điện thoại Việt Nam 10 chữ số (bắt đầu bằng 03, 05, 07, 08 hoặc 09).';
    }

    if (!cleaned.province_id || !Utils.isNumeric(cleaned.province_id)) {
        errors.province_id = 'Vui lòng chọn Tỉnh / Thành phố nhận hàng.';
    }

    if (!cleaned.district_id || !Utils.isNumeric(cleaned.district_id)) {
        errors.district_id = 'Vui lòng chọn Quận / Huyện nhận hàng.';
    }

    if (!cleaned.ward_id || !Utils.isNumeric(cleaned.ward_id)) {
        errors.ward_id = 'Vui lòng chọn Phường / Xã nhận hàng.';
    }

    if (!cleaned.detailed_address) {
        errors.detailed_address = 'Vui lòng nhập địa chỉ chi tiết (số nhà, tên đường, tên ngõ...).';
    } else if (cleaned.detailed_address.length < 5) {
        errors.detailed_address = 'Địa chỉ chi tiết phải có tối thiểu 5 ký tự.';
    } else if (cleaned.detailed_address.length > 255) {
        errors.detailed_address = 'Địa chỉ chi tiết không được vượt quá 255 ký tự.';
    }

    return {
        isValid: Object.keys(errors).length === 0,
        errors,
        cleanedData: cleaned,
    };
}
