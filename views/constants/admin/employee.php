<?php 

include __DIR__ . '/../../bases/BaseRender.php';

class EmployeeRender extends BaseRender {
    public function __construct() {
        $this->describe = [
            [
                "title" => "QUẢN LÝ THÔNG TIN NHÂN VIÊN",
                "description" => "Đây là trang quản lý thông tin nhân viên, với các chức năng..."
            ]
        ];

        $this->table = [
            [
                "name" => "ID",
                "render" => fn($item) => "<span>{$item["id"]}</span>"
            ],
                        [
                "name" => "Họ",
                "render" => fn($item) => "<span>{$item["lastName"]}</span>"
            ],
            [
                "name" => "Tên đệm",
                "render" => fn($item) => "<span>{$item["middleName"]}</span>"
            ],
            [
                "name" => "Tên",
                "render" => fn($item) => "<span>{$item["firstName"]}</span>"
            ],
            [
                "name" => "Email",
                "render" => fn($item) => "<span>{$item["email"]}</span>"
            ],
            [
                "name" => "Số điện thoại",
                "render" => fn($item) => "<span>{$item["phone"]}</span>"
            ],
            // [
            //     "name" => "Phòng ban",
            //     "className" => "text-center",
            //     "render" => fn($item) => "<span>{$item["department"]}</span>"
            // ],
            // [
            //     "name" => "Lương",
            //     "className" => "text-right",
            //     "render" => fn($item) => "<span>" . number_format($item["salary"], 0, ',', '.') . " đ</span>"
            // ],
            // [
            //     "name" => "Trạng thái",
            //     "className" => "text-center",
            //     "render" => function($item) {
            //         $statusColors = [
            //             "Đang làm" => "#22c55e",
            //             "Nghỉ phép" => "#facc15", 
            //             "Nghỉ việc" => "#ef4444",  
            //             "Tạm hoãn" => "#3b82f6"  
            //         ];
            //         $color = $statusColors[$item["status"]] ?? "#9ca3af";
            //         return "<span style='padding:4px 8px;border-radius:12px;background:{$color};color:#fff;font-size:12px;'>{$item["status"]}</span>";
            //     }
            // ]
        ];

        $this->buttonAction = [
            [
                "label" => "Xuất báo cáo ra Exel",
                "icon" => "<i class='fa-solid fa-file-export'></i>",
                "className" => "btn-exel",
                "onClick" => "(function(){var s=document.querySelector('.custom-sheet'); if(s&&s.open) s.open();})()",
            ],
        ];

        $this->buttonTableActions = [
            // [
            //     "icon" => "<i class='fa fa-edit'></i>",
            //     "className" => "btn btn-edit",
            //     "method" => "update",
            // ],
            // [
            //     "icon" => "<i class='fa fa-trash'></i>",
            //     "className" => "btn btn-delete",
            //     "method" => "delete",
            //     "onClick" => "destroy(%id%)"
            // ],
            [
                "icon" => "<i class='fa fa-search'></i>",
                "className" => "btn btn-view",
                "method" => "view",
            ],
        ];
    }
}