@extends('layouts.dashboard')

@section('title', 'Tạo KPI mới')

@push('styles')
<style>
    .form-container {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        max-width: 800px;
        margin: 0 auto;
    }

    .form-title {
        font-size: 24px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 30px;
        text-align: center;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        font-weight: 500;
        color: #495057;
        margin-bottom: 8px;
        display: block;
    }

    .form-control {
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 12px 15px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .btn-submit {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 12px 30px;
        font-size: 16px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }

    .btn-cancel {
        background: #6c757d;
        color: white;
        border: none;
        border-radius: 8px;
        padding: 12px 30px;
        font-size: 16px;
        font-weight: 500;
        text-decoration: none;
        margin-right: 10px;
    }

    .btn-cancel:hover {
        background: #5a6268;
        color: white;
    }
</style>
@endpush

@section('content')
<div class="form-container">
    <h1 class="form-title">Tạo KPI mới</h1>

    <form method="POST" action="{{ route('kpi.store') }}">
        @csrf

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="Ten_kpi" class="form-label">Tên KPI *</label>
                    <input type="text" class="form-control @error('Ten_kpi') is-invalid @enderror"
                           id="Ten_kpi" name="Ten_kpi" value="{{ old('Ten_kpi') }}" required>
                    @error('Ten_kpi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="Chi_tieu" class="form-label">Chỉ tiêu *</label>
                    <input type="number" class="form-control @error('Chi_tieu') is-invalid @enderror"
                           id="Chi_tieu" name="Chi_tieu" value="{{ old('Chi_tieu') }}" required>
                    @error('Chi_tieu')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="Donvi_tinh" class="form-label">Đơn vị tính *</label>
                    <input type="text" class="form-control @error('Donvi_tinh') is-invalid @enderror"
                           id="Donvi_tinh" name="Donvi_tinh" value="{{ old('Donvi_tinh') }}" required>
                    @error('Donvi_tinh')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="Do_uu_tien" class="form-label">Độ ưu tiên *</label>
                    <select class="form-control @error('Do_uu_tien') is-invalid @enderror"
                            id="Do_uu_tien" name="Do_uu_tien" required>
                        <option value="">Chọn độ ưu tiên</option>
                        <option value="Rất gấp" {{ old('Do_uu_tien') == 'Rất gấp' ? 'selected' : '' }}>Rất gấp</option>
                        <option value="Gấp" {{ old('Do_uu_tien') == 'Gấp' ? 'selected' : '' }}>Gấp</option>
                        <option value="Trung Bình" {{ old('Do_uu_tien') == 'Trung Bình' ? 'selected' : '' }}>Trung Bình</option>
                        <option value="Không" {{ old('Do_uu_tien') == 'Không' ? 'selected' : '' }}>Không</option>
                    </select>
                    @error('Do_uu_tien')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="ID_loai_kpi" class="form-label">Loại KPI *</label>
                    <select class="form-control @error('ID_loai_kpi') is-invalid @enderror"
                            id="ID_loai_kpi" name="ID_loai_kpi" required>
                        <option value="">Chọn loại KPI</option>
                        @foreach($loaiKpis as $loaiKpi)
                            <option value="{{ $loaiKpi->ID_loai_kpi }}"
                                    {{ old('ID_loai_kpi') == $loaiKpi->ID_loai_kpi ? 'selected' : '' }}>
                                {{ $loaiKpi->Ten_loai_kpi }}
                            </option>
                        @endforeach
                    </select>
                    @error('ID_loai_kpi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="ID_user" class="form-label">Người thực hiện</label>
                    <select class="form-control @error('ID_user') is-invalid @enderror"
                            id="ID_user" name="ID_user">
                        <option value="">Chọn người thực hiện</option>
                        @foreach($users as $user)
                            <option value="{{ $user->ID_user }}"
                                    {{ old('ID_user') == $user->ID_user ? 'selected' : '' }}>
                                {{ $user->Ho_ten }} ({{ $user->Email }})
                            </option>
                        @endforeach
                    </select>
                    @error('ID_user')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="ID_phongban" class="form-label">Phòng ban</label>
                    <select class="form-control @error('ID_phongban') is-invalid @enderror"
                            id="ID_phongban" name="ID_phongban">
                        <option value="">Chọn phòng ban</option>
                        @foreach($phongbans as $phongban)
                            <option value="{{ $phongban->ID_phongban }}"
                                    {{ old('ID_phongban') == $phongban->ID_phongban ? 'selected' : '' }}>
                                {{ $phongban->Ten_phongban }}
                            </option>
                        @endforeach
                    </select>
                    @error('ID_phongban')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="Ngay_batdau" class="form-label">Ngày bắt đầu *</label>
                    <input type="date" class="form-control @error('Ngay_batdau') is-invalid @enderror"
                           id="Ngay_batdau" name="Ngay_batdau" value="{{ old('Ngay_batdau') }}" required>
                    @error('Ngay_batdau')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="Ngay_ketthuc" class="form-label">Ngày kết thúc *</label>
                    <input type="date" class="form-control @error('Ngay_ketthuc') is-invalid @enderror"
                           id="Ngay_ketthuc" name="Ngay_ketthuc" value="{{ old('Ngay_ketthuc') }}" required>
                    @error('Ngay_ketthuc')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="Mo_ta" class="form-label">Mô tả</label>
            <textarea class="form-control @error('Mo_ta') is-invalid @enderror"
                      id="Mo_ta" name="Mo_ta" rows="4">{{ old('Mo_ta') }}</textarea>
            @error('Mo_ta')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="text-center">
            <a href="{{ route('kpi.index') }}" class="btn-cancel">Hủy</a>
            <button type="submit" class="btn-submit">Tạo KPI</button>
        </div>
    </form>
</div>
@endsection
