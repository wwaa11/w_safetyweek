@extends('layouts')
@section('content')
    <div class="m-auto w-full md:w-3/4 p-6 bg-white rounded shadow">
        <a href="{{ env('APP_URL') }}/">
            <div class="mb-3 text-center cursor-pointer text-red-500 border border-red-500 rounded">
                เปลี่ยนรอบการลงทะเบียน
            </div>
        </a>
        <div>
            <div class="flex">
                <div><img class="w-12" src="{{ asset('images/logo.png') }}" alt=""></div>
                <div class="flex-grow text-2xl text-center mb-3 text-orange-500">
                    ตรวจรอบการลงทะเบียนเข้าร่วมกิจกรรม<br>Safety week
                    2024
                </div>
            </div>
            <div class="my-3">
                <div class="flex-grow">
                    <label class="block mb-1" for="">รหัสพนักงาน</label>
                    <input placeholder="กรณี Part time, Outsource ไม่ต้องระบุ"
                        class="block mb-3 w-full p-1 border rounded border-gray-300" id="userid" type="text">
                </div>
                <div class="flex-grow">
                    <label class="block mb-1" for="">ชื่อ-นามสกุล <span class="text-red-500">*</span></label>
                    <input class="block mb-3 w-full p-1 border rounded border-gray-300" id="name" type="text">
                </div>
            </div>
        </div>
        <div id="content" class="bg-green-100 p-6 rounded shadow hidden">
            <div>
                <div class="text-center text-lg">ข้อมูลการลงทะเบียน</div>
                <div>ชื่อ-นามสกุล</div>
                <div id="check_name"></div>
                <div>แผนก/สาขา</div>
                <div id="check_dept"></div>
                <div>วันที่ </div>
                <div id="check_date" class="text-red-500"></div>
                <div>ชื่อฐาน </div>
                <div id="check_level"></div>
            </div>
        </div>
        <div class="text-center mt-3">
            <button onclick="submit()" id="submiting"
                class="w-full md:w-1/3 p-3 rounded border-4 border-green-400 text-green-400 hover:bg-green-400 hover:text-white">ตรวจสอบ</button>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        async function submit() {
            var userid = $('#userid').val()
            var name = $('#name').val()
            check = true
            if (name == '') {
                check = false
            }
            if (check) {
                const formData = new FormData()
                formData.append('userid', userid)
                formData.append('name', name)
                const res = await axios.post("{{ env('APP_URL') }}" + "/search", formData, {
                    "Content-Type": "multipart/form-data"
                }).then((res) => {
                    if (res['data']['status'] == 2) {
                        Swal.fire({
                            title: res['data']['descriptaion'],
                            icon: 'error',
                            confirmButtonText: 'ตกลง',
                            confirmButtonColor: 'red'
                        })
                    } else {
                        $('#content').show()
                        $('#check_name').html(res['data']['name'])
                        $('#check_dept').html(res['data']['dept'])
                        $('#check_date').html(res['data']['date'])
                        $('#check_level').html(res['data']['level'])
                    }
                })
            } else {
                Swal.fire({
                    title: 'กรุณาระบุรายละเอียดให้ครบ',
                    icon: 'error',
                    confirmButtonText: 'ตกลง',
                    confirmButtonColor: 'red'
                })
            }
        }
    </script>
@endsection
