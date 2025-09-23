<!DOCTYPE html>
<html>
<head>
    <title>Checkup Invoice</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        h2 { text-align: center; }
        .invoice-box { border: 1px solid #ccc; padding: 20px; max-width: 600px; margin: auto; }
        table { width: 100%; }
        th, td { padding: 8px; text-align: left; }
        .footer { margin-top: 20px; text-align: center; font-size: 12px; color: #777; }
        .btn-print { margin-top: 20px; text-align: center; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <h2>Patient Checkup Invoice</h2>
        <table>
            <tr>
                <th>Patient Name:</th>
                <td>{{ $checkup->patient->name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Doctor:</th>
                <td>{{ $checkup->doctor->name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Date:</th>
                <td>{{ \Carbon\Carbon::parse($checkup->date)->format('d-m-Y') }}</td>
            </tr>

             <tr>
                <th>Phone:</th>
                <td>{{ $checkup->patient->phone ?? 'N/A' }}</td>
            </tr>

            <tr>
                <th>Diagnosis:</th>
                <td>{{ $checkup->diagnosis }}</td>
            </tr>
            <tr>
                <th>Checkup Fee:</th>
                <td>Rs. {{ $checkup->fee }}</td>
            </tr>
        </table>

        <div class="btn-print">
            <button onclick="window.print()">Print</button>
        </div>
    </div>

@endsection
@push('script')
    <!--plugins-->
    <script src="{{ URL::asset('build/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
    <script src="{{ URL::asset('build/plugins/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ URL::asset('build/plugins/simplebar/js/simplebar.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/main.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // PDF Export functionality - more specific selector
    const pdfButton = document.querySelector('.btn-danger.btn-sm.me-2');

    if (pdfButton) {
        pdfButton.addEventListener('click', function() {
            const element = document.querySelector('.printpdf');
            const options = {
                margin: [5, 5, 5, 5],
                filename: 'BodyExperts-Invoice-101714.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: {
                    scale: 2,
                    useCORS: true,
                    logging: false
                },
                jsPDF: {
                    unit: 'mm',
                    format: 'a4',
                    orientation: 'portrait'
                }
            };

            // Show loading indicator
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Generating PDF...';
            this.style.pointerEvents = 'none';

            html2pdf().set(options).from(element).save().finally(() => {
                // Restore button text after 2 seconds
                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.style.pointerEvents = 'auto';
                }, 2000);
            });
        });
    } else {
        console.error('PDF button not found!');
    }
});
</script>
@endpush

