@extends('layouts')
@section('content')
    <div class="m-auto w-full md:w-3/4 p-6 bg-white rounded shadow">
        <a href="{{ env('APP_URL') }}/check">
            <div class="mb-3 text-center cursor-pointer text-red-500 border border-red-500 rounded">
                ตรวจสอบรอบการลงทะเบียน
            </div>
        </a>
        <div class="flex">
            <div><img class="w-12" src="{{ asset('images/logo.png') }}" alt=""></div>
            <div class="flex-grow text-2xl text-center mb-3 text-orange-500">ลงทะเบียนเข้าร่วมกิจกรรม<br>Safety week 2024
            </div>
            <div></div>
        </div>
        <div class="my-3">
            <div class="mb-1">ประเภทบุคลากร <span class="text-red-500">*</span></div>
            <div>
                <input onchange="changeType('1')" value="แพทย์" name="type" id="type_1" type="radio">
                <label for="type_1">แพทย์</label>
            </div>
            <div>
                <input onchange="changeType('2')" value="พนักงาน Full time" name="type" id="type_2" type="radio">
                <label for="type_2">พนักงาน Full time</label>
            </div>
            <div>
                <input onchange="changeType('3')" value="พนักงาน Part time" name="type" id="type_3" type="radio">
                <label for="type_3">พนักงาน Part time</label>
            </div>
            <div>
                <input onchange="changeType('4')" value="พนักงาน Out source" name="type" id="type_4" type="radio">
                <label for="type_4">พนักงาน Out source</label>
            </div>
        </div>
        <div>
            <label class="block mb-1" for="">รหัสพนักงาน <span class="text-red-500 text-xs">*กรณี Part time,
                    Outsource
                    ไม่ต้องระบุ</span></label>
            <input class="block w-full p-1 border rounded border-gray-300" id="userid" type="text">
            <label class="block mb-1 mt-3" for="">ชื่อ-นามสกุล <span class="text-red-500">*</span></label>
            <input class="block w-full p-1 border rounded border-gray-300" id="name" type="text">
        </div>
        <div id="dept_section" class="hidden">
            <label class="block mt-3 mb-1" for="">เลือกแผนก/สาขา<span class="text-red-500">*</span></label>
            <select id="dept_doctor" name="dept_doctor" class="block w-full p-1 border rounded border-gray-300">
                <option value="">เลือกสาขา</option>
                @foreach ($doctors as $item)
                    <option value="{{ $item->name }}">{{ $item->name }}</option>
                @endforeach
            </select>
            <select id="dept_user" name="dept_user" class="block w-full p-1 border rounded border-gray-300">
                <option value="">เลือกแผนก</option>
                @foreach ($depts as $item)
                    <option value="{{ $item->name }}">{{ $item->name }}</option>
                @endforeach
            </select>
            <select id="dept_outsource" name="dept_outsource" class="block w-full p-1 border rounded border-gray-300">
                <option value="">ระบุ</option>
                <option value="แม่บ้าน">แม่บ้าน</option>
                <option value="รปภ">รปภ</option>
                <option value="CP">CP</option>
            </select>
        </div>
        <div>
            <label class="mt-3 block mb-1" for="">เลือกรอบที่ต้องการเข้าร่วม <span
                    class="text-red-500">*</span></label>
            <div>
                <div class="md:hidden">
                    @foreach ($data as $day)
                        <div class="mb-3">
                            <div class="p-3 text-center border border-collapse border-gray-300">{{ $day['date'] }},
                                {{ $day['day'] }} </div>
                            @foreach ($day['slot'] as $item)
                                <div class="p-3 border border-collapse border-gray-300  @if ($item['slot'] > 0) bg-green-200 @else bg-red-400" @endif"
                                    id="mobile_{{ $item['round'] }}" onclick="selectSlot('mobile_{{ $item['round'] }}')">
                                    {{ $item['time'] }}
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
                <table class="w-full hidden md:table">
                    <thead>
                        <th class="p-3 border border-collapse border-gray-300">วัน</th>
                        <th class="p-3 border border-collapse border-gray-300">วันที่</th>
                        <th class="p-3 border border-collapse border-gray-300">09.00 - 10.30 น.</th>
                        <th class="p-3 border border-collapse border-gray-300">10.30 - 12.00 น.</th>
                        <th class="p-3 border border-collapse border-gray-300">13.30 - 15.00 น.</th>
                        <th class="p-3 border border-collapse border-gray-300">15.00 - 16.30 น.</th>
                    </thead>
                    <tbody>
                        @foreach ($data as $day)
                            <tr class="text-center">
                                <td class="p-3 border border-collapse border-gray-300 text-left">{{ $day['day'] }}</td>
                                <td class="p-3 border border-collapse border-gray-300 ">{{ $day['date'] }}</td>
                                @foreach ($day['slot'] as $item)
                                    <td id="web_{{ $item['round'] }}" onclick="selectSlot('web_{{ $item['round'] }}')"
                                        @if ($item['slot'] > 0) class="p-3 border border-collapse border-gray-300 cursor-pointer bg-green-200" @else class="bg-red-400" @endif>
                                        รอบที่ {{ $item['round'] }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="w-full text-center mt-6">
            <button id="wating" class="bg-yellow-400 w-full md:w-1/3 p-3 rounded hidden">กรุณารอสักครู่...</button>
            <button onclick="submit()" id="submiting"
                class="w-full md:w-1/3 p-3 rounded border-4 border-green-400 text-green-400 hover:bg-green-400 hover:text-white">ลงทะเบียน</button>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        var select = null

        function changeType(type) {
            $('#dept_section').show()
            if (type == 1) {
                $('#dept_doctor').show()
                $('#dept_user').hide()
                $('#dept_outsource').hide()
            } else if (type == 2 || type == 3) {
                $('#dept_doctor').hide()
                $('#dept_user').show()
                $('#dept_outsource').hide()
            } else {
                $('#dept_doctor').hide()
                $('#dept_user').hide()
                $('#dept_outsource').show()
            }
        }

        function selectSlot(id) {
            $('#' + id).removeClass('bg-green-200')
            $('#' + id).addClass('bg-blue-300')
            $('#' + select).removeClass('bg-blue-300')
            $('#' + select).addClass('bg-green-200')
            if (id == select) {
                $('#' + id).addClass('bg-blue-300')
            }
            select = id
        }

        async function submit() {
            $('#wating').show()
            $('#submiting').hide()

            var type = $('input[name="type"]:checked').val()
            var userid = $('#userid').val()
            var name = $('#name').val()
            var dept_doctor = $('#dept_doctor option:selected').val()
            var dept_user = $('#dept_user option:selected').val()
            var dept_outsource = $('#dept_outsource option:selected').val()
            var check = true

            if (type == undefined) {
                check = false
            }
            if ((type == 'แพทย์' || type == 'พนักงาน Full time') && userid == '') {
                check = false
            }
            if (name == '') {
                check = false
            }
            if (dept_doctor == '' && dept_user == '' && dept_outsource == '') {
                check = false
            }
            if (select == null) {
                check = false
            }
            if (check) {
                const formData = new FormData()
                formData.append('type', type)
                formData.append('userid', userid)
                formData.append('name', name)
                formData.append('dept_doctor', dept_doctor)
                formData.append('dept_user', dept_user)
                formData.append('dept_outsource', dept_outsource)
                formData.append('round', select)
                const res = await axios.post("{{ env('APP_URL') }}" + "/save", formData, {
                    "Content-Type": "multipart/form-data"
                }).then((res) => {
                    Swal.fire({
                        title: res['data']['description'],
                        icon: 'success',
                        confirmButtonText: 'ตกลง',
                        confirmButtonColor: 'green'
                    }).then(function(isConfirmed) {
                        if (isConfirmed) {
                            window.location.reload()
                        }
                    })
                })
            } else {
                Swal.fire({
                    title: 'กรุณาระบุรายละเอียดให้ครบ',
                    icon: 'error',
                    confirmButtonText: 'ตกลง',
                    confirmButtonColor: 'red'
                })
                $('#wating').hide()
                $('#submiting').show()
            }

        }
    </script>
@endsection
