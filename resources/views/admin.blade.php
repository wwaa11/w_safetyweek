@extends('layouts')
@section('content')
    <div class="w-full md:w-3/4 m-auto p-6 border shadow">
        <div class="text-center text-2xl text-orange-500">รายชื่อผู้เข้าร่วมกิจกรรม</div>
        <div class="text-right"><button class="text-green-600 text-2xl" onclick="exportFN()">Export to Excel</button></div>
        <table class="w-full" id="table">
            <tbody>
                @foreach ($data as $key_day => $day)
                    <tr class="bg-gray-400 border">
                        <td class="p-2" colspan="4">วันที่ {{ $key_day }}</td>
                    </tr>
                    @foreach ($day as $key_time => $time)
                        <tr class="border">
                            <td class="p-2" colspan="4">เวลา {{ $key_time }}</td>
                        </tr>
                        @foreach ($time as $key_level => $level)
                            <tr class="bg-red-200">
                                <td class="p-2" colspan="4">{{ $key_level }}</td>
                            </tr>
                            <tr class="bg-gray-200 border">
                                <td class="p-1">ลำดับ</td>
                                <td class="p-1">รหัสพนักงาน</td>
                                <td class="p-1">ชื่อ-นามสกุล</td>
                                <td class="p-1">แผนก/สาขา</td>
                            </tr>
                            @foreach ($level as $index => $user)
                                <tr class="border">
                                    <td class="p-1">{{ $index + 1 }}</td>
                                    <td class="p-1">{{ $user['userid'] }}</td>
                                    <td class="p-1">{{ $user['name'] }}</td>
                                    <td class="p-1">{{ $user['position'] }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                    @endforeach
                    <tr class="">
                        <td colspan="4" class="p-1">&nbsp;</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
@section('scripts')
    <script>
        function exportFN() {
            TableToExcel.convert(document.getElementById("table"));
        }
    </script>
@endsection
