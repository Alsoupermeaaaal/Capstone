<?php
// Include the main TCPDF library (search for installation path).
require_once('TCPDF-main/tcpdf.php');

// Extend TCPDF with custom functions
class MYPDF extends TCPDF {

    public function LoadData($startDate, $endDate, $salonId) {
        include __DIR__ . '/db_connect.php'; // Use absolute path
        
        if (!$conn) {
            error_log("Connection failed!", 3, "errors.log");
            exit;
        }

        // SQL query to join book and services
        $select = "SELECT 
                        b.bookID, 
                        CONCAT(r.ownerfname, ' ', r.ownerlname) AS ownerName, 
                        p.petname AS petName, 
                        b.salonid, 
                        GROUP_CONCAT(s.servicename SEPARATOR ', ') AS serviceNames, 
                        b.date, 
                        b.time, 
                        b.paymentmethod, 
                        b.is_cancelled, 
                        b.cancel_date, 
                        b.status, 
                        b.paymentprice 
                    FROM book b 
                    JOIN registration_info r ON b.ownerID = r.ownerID
                    JOIN petinfo p ON b.petid = p.petid
                    JOIN services s ON FIND_IN_SET(s.serviceid, b.serviceid) > 0 
                    WHERE b.date BETWEEN ? AND ? AND b.salonid = ? 
                    GROUP BY b.bookID, r.ownerfname, r.ownerlname, p.petname, b.salonid, b.date, b.time, b.paymentmethod, b.is_cancelled, b.cancel_date, b.status, b.paymentprice
                    ORDER BY b.date DESC, b.time DESC"; // Order by date and time in descending order
        
        $stmt = $conn->prepare($select);
        $stmt->bind_param("ssi", $startDate, $endDate, $salonId);
        $stmt->execute();
        $result = $stmt->get_result();

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row; // Push each row into the data array
        }
        return $data; // Return the data array
    }

// Colored table
public function ColoredTable($header, $data) {
    // Colors, line width and bold font
    $this->SetFillColor(255, 0, 0);
    $this->SetTextColor(255);
    $this->SetDrawColor(128, 0, 0);
    $this->SetLineWidth(0.3);
    $this->SetFont('', 'B', 8); // Reduced header font size

    // Adjusted column widths to fit content better
    $w = array(12, 30, 25, 15, 55, 18, 18, 25, 15, 18, 12, 20);
    $num_headers = count($header);

    // Print header
    for ($i = 0; $i < $num_headers; ++$i) {
        $this->Cell($w[$i], 6, $header[$i], 1, 0, 'C', 1);
    }
    $this->Ln(); // Move to the next line

    // Restore color and font for data
    $this->SetFillColor(224, 235, 255);
    $this->SetTextColor(0);
    $this->SetFont('', '', 6); // Reduced data font size for better fit

    // Data Rows
    $fill = 0;
    foreach ($data as $row) {
        $this->Cell($w[0], 5, htmlspecialchars($row['bookID']), 'LR', 0, 'C', $fill);
        $this->Cell($w[1], 5, htmlspecialchars($row['ownerName']), 'LR', 0, 'L', $fill);
        $this->Cell($w[2], 5, htmlspecialchars($row['petName']), 'LR', 0, 'L', $fill);
        $this->Cell($w[3], 5, htmlspecialchars($row['salonid']), 'LR', 0, 'C', $fill);

        // MultiCell for service names (wrapping)
        $x = $this->GetX();
        $this->MultiCell($w[4], 5, htmlspecialchars($row['serviceNames']), 'LR', 'L', $fill);
        $this->SetX($x + $w[4]);

        $this->Cell($w[5], 5, htmlspecialchars($row['date']), 'LR', 0, 'C', $fill);
        $this->Cell($w[6], 5, htmlspecialchars($row['time']), 'LR', 0, 'C', $fill);
        $this->Cell($w[7], 5, htmlspecialchars($row['paymentmethod']), 'LR', 0, 'C', $fill);
        $this->Cell($w[8], 5, htmlspecialchars($row['is_cancelled']), 'LR', 0, 'C', $fill);
        $this->Cell($w[9], 5, htmlspecialchars($row['cancel_date']), 'LR', 0, 'C', $fill);
        $this->Cell($w[10], 5, htmlspecialchars($row['status']), 'LR', 0, 'C', $fill);
        $this->Cell($w[11], 5, htmlspecialchars($row['paymentprice']), 'LR', 0, 'R', $fill);

        $this->Ln(); // Move to the next row
        $fill = !$fill; // Toggle fill
    }

    // Bottom border
    $this->Cell(array_sum($w), 0, '', 'T');
}
}

// Create new PDF document with landscape orientation
$pdf = new MYPDF('L', PDF_UNIT, 'A4', true, 'UTF-8', false); // Set page size to A4

// Set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Your Name');
$pdf->SetTitle('Service Report');
$pdf->SetSubject('Service Report');
$pdf->SetKeywords('TCPDF, PDF, report, services');

// Set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// Set margins
$pdf->SetMargins(8, 8, 8); // Adjusted margins
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// Set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// Add a page
$pdf->AddPage();

// Get data from the POST request
$startDate = $_POST['startDate'] ?? null;
$endDate = $_POST['endDate'] ?? null;
$salonId = $_POST['salonid'] ?? null;

// Check if the required parameters are set
if ($startDate && $endDate && $salonId) {
    $data = $pdf->LoadData($startDate, $endDate, $salonId);

    // Define the header
    $header = array('Book ID', 'Owner Name', 'Pet Name', 'Salon ID', 'Service Names', 'Date', 'Time', 'Payment Method', 'Is Cancelled', 'Cancel Date', 'Status', 'Payment Price');

    // Print colored table
    $pdf->ColoredTable($header, $data);

    // Close and output PDF document
    $pdf->Output('service_report.pdf', 'I');
} else {
    // Handle the case where parameters are missing
    echo "Error: Missing parameters.";
}
?>
