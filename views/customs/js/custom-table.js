function destroy(entity, id, options = {}) {
    const entityEndpoints = {
        product: 'ProductApi.php',
        catalogue: 'UserCatalogueApi.php', 
        user: 'UserApi.php',
        productCategory: 'ProductCategoryApi.php'
    };

    const entityNames = {
        product: 'sản phẩm',
        category: 'danh mục',
        user: 'người dùng', 
        customer: 'khách hàng',
        employee: 'nhân viên',
        productCategory: 'danh mục sản phẩm'
    };

    const endpoint = entityEndpoints[entity];
    const entityName = entityNames[entity] || 'dữ liệu';

    if (!endpoint) {
        console.error(`Không tìm thấy endpoint cho thực thể: ${entity}`);
        alert('Thực thể không được hỗ trợ');
        return;
    }

    const defaultMessage = `Bạn có chắc chắn muốn xóa ${entityName} này không?`;
    const defaultSuccessMessage = `Xóa ${entityName} thành công!`;
    const defaultErrorMessage = `Xóa ${entityName} thất bại`;

    const confirmMessage = options.customMessage || defaultMessage;
    const successMessage = options.customSuccessMessage || defaultSuccessMessage;
    const errorMessage = options.customErrorMessage || defaultErrorMessage;

    if (!confirm(confirmMessage)) {
        return;
    }

    const url = `http://localhost/webbanhang/apis/${endpoint}?route=destroy&id=${id}`;

    fetch(url, {
        method: 'DELETE'
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert(successMessage);
            
            // Gọi callback onSuccess nếu có
            if (typeof options.onSuccess === 'function') {
                options.onSuccess(data);
            } else {
                // Mặc định reload trang
                location.reload();
            }
        } else {
            const failureMessage = `${errorMessage}: ${data.message || 'Không xác định'}`;
            alert(failureMessage);
            
            if (typeof options.onError === 'function') {
                options.onError(data);
            }
        }
    })
    .catch(error => {
        console.error('Lỗi:', error);
        const finalErrorMessage = `${errorMessage}: ${error.message || 'Lỗi kết nối'}`;
        alert(finalErrorMessage);
        
        if (typeof options.onError === 'function') {
            options.onError(error);
        }
    });
}

function destroyProduct(productId, options = {}) {
    return destroy('product', productId, options);
}

function destroyUserCatalogue(catalogueId, options = {}) {
    return destroy('category', catalogueId, options);
}

function destroyUser(userId, options = {}) {
    return destroy('user', userId, options);
}

function destroyProductCategory(categoryId, options = {}) {
    return destroy('productCategory', categoryId, options);
}



function edit(entity, id, options = {}) {
    // Map các thực thể với URL API và selector sheet
    const entityConfig = {
        user: {
            apiUrl: `http://localhost/webbanhang/apis/UserApi.php?route=show&id=${id}`,
            sheetSelector: '.custom-sheet.user-sheet',
            reloadUrl: '?edit=' + id
        },
        product: {
            apiUrl: `http://localhost/webbanhang/apis/ProductApi.php?route=show&id=${id}`,
            sheetSelector: '.custom-sheet.product-sheet',
            reloadUrl: '?edit=' + id
        },
        catalogue: {
            apiUrl: `http://localhost/webbanhang/apis/UserCatalogueApi.php?route=show&id=${id}`,
            sheetSelector: '.custom-sheet.user-sheet',
            reloadUrl: '?edit=' + id
        },
        productCategory: {
            apiUrl: `http://localhost/webbanhang/apis/ProductCategoryApi.php?route=show&id=${id}`,
            sheetSelector: '.custom-sheet.product-category-sheet',
            reloadUrl: '?edit=' + id
        }
    };

    const config = entityConfig[entity];
    
    if (!config) {
        console.error(`Không tìm thấy config cho thực thể: ${entity}`);
        alert('Thực thể không được hỗ trợ');
        return;
    }

    // Tải dữ liệu từ API
    fetch(config.apiUrl)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Mở sheet
                const sheet = document.querySelector(config.sheetSelector);
                if (sheet && sheet.open) {
                    sheet.open();
                    
                    // Cập nhật URL để có thể reload với edit mode
                    if (options.updateUrl !== false) {
                        const newUrl = window.location.pathname + config.reloadUrl;
                        window.history.pushState({}, '', newUrl);
                    }
                    
                    // Gọi callback onSuccess nếu có
                    if (typeof options.onSuccess === 'function') {
                        options.onSuccess(data.data);
                    }
                } else {
                    console.error('Không tìm thấy sheet hoặc sheet không có method open');
                    alert('Không thể mở form chỉnh sửa');
                }
            } else {
                const errorMessage = `Không thể tải dữ liệu: ${data.message || 'Không xác định'}`;
                alert(errorMessage);
                
                // Gọi callback onError nếu có
                if (typeof options.onError === 'function') {
                    options.onError(data);
                }
            }
        })
        .catch(error => {
            console.error('Lỗi khi tải dữ liệu:', error);
            alert('Có lỗi xảy ra khi tải dữ liệu');
            
            // Gọi callback onError nếu có
            if (typeof options.onError === 'function') {
                options.onError(error);
            }
        });
}

// Các hàm wrapper cho từng thực thể
function editProduct(productId, options = {}) {
    return edit('product', productId, options);
}

function editCatalogue(catalogueId, options = {}) {
    return edit('catalogue', catalogueId, options);
}

function editUser(userId, options = {}) {
    return edit('user', userId, options);
}
function editProductCategory(categoryId, options = {}) {
    return edit('productCategory', categoryId, options);
}