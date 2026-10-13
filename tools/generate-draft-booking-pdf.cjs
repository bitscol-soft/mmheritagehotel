#!/usr/bin/env node
/**
 * Generates a modern executive A4 vector PDF:
 *   public/assets/docs/draft-reservation-summary.pdf
 * "Whole Booking Preview — Draft Reservation Summary PDF"
 */
const fs = require('fs');
const path = require('path');

function esc(str) {
    return String(str)
        .replace(/\\/g, '\\\\')
        .replace(/\(/g, '\\(')
        .replace(/\)/g, '\\)');
}

function buildContentStream() {
    const ops = [];
    const rgbFill = (r, g, b) => ops.push(`${(r / 255).toFixed(3)} ${(g / 255).toFixed(3)} ${(b / 255).toFixed(3)} rg`);
    const rgbStroke = (r, g, b) => ops.push(`${(r / 255).toFixed(3)} ${(g / 255).toFixed(3)} ${(b / 255).toFixed(3)} RG`);
    const rectFill = (x, y, w, h) => ops.push(`${x} ${y} ${w} ${h} re f`);
    const rectStroke = (x, y, w, h, lw = 0.75) => ops.push(`${lw} w ${x} ${y} ${w} ${h} re S`);
    const rectFillStroke = (x, y, w, h, lw = 0.75) => ops.push(`${lw} w ${x} ${y} ${w} ${h} re B`);
    const line = (x1, y1, x2, y2, lw = 0.75) => ops.push(`${lw} w ${x1} ${y1} m ${x2} ${y2} l S`);
    const text = (x, y, size, font, str, r = 15, g = 23, b = 42) => {
        rgbFill(r, g, b);
        ops.push(`BT /${font} ${size} Tf 1 0 0 1 ${x} ${y} Tm (${esc(str)}) Tj ET`);
    };

    // Page Background (White A4: 595 x 842)
    rgbFill(255, 255, 255);
    rectFill(0, 0, 595, 842);

    // Outer Document Card Border
    rgbStroke(203, 213, 225);
    rectStroke(24, 24, 547, 794, 0.9);

    // 1. Luxury Navy Header Banner (y=732..818)
    rgbFill(15, 23, 42);
    rectFill(24, 732, 547, 86);
    // Gold Bottom Trim on Header
    rgbFill(245, 158, 11);
    rectFill(24, 729, 547, 3);

    // Gold Monogram Crest Box
    rgbFill(245, 158, 11);
    rectFill(38, 752, 46, 46);
    text(45, 770, 13, 'F2', 'M&M', 15, 23, 42);

    // Hotel Title & Address
    text(94, 792, 7.5, 'F2', 'FIVE-STAR BOUTIQUE HOSPITALITY  |  DHAKA, BANGLADESH', 252, 211, 77);
    text(94, 774, 15, 'F2', 'M&M HERITAGE HOTEL & SUITES', 255, 255, 255);
    text(94, 760, 8, 'F1', 'Plot 42, Road 90, Gulshan-2, Dhaka-1212  |  BIN: 004192837-0101', 203, 213, 225);
    text(94, 748, 8, 'F1', 'Front Desk: +880 2-55051920  |  reservations@mmheritagehotel.com', 148, 163, 184);

    // Right Header Document Meta Box
    rgbFill(30, 41, 59);
    rgbStroke(71, 85, 105);
    rectFillStroke(368, 743, 190, 64, 0.75);
    text(377, 795, 7, 'F2', 'WHOLE BOOKING PREVIEW - DRAFT PDF', 147, 197, 253);

    // Status Pill (Emerald)
    rgbFill(5, 150, 105);
    rectFill(377, 777, 128, 13);
    text(383, 781, 7.5, 'F2', 'DRAFT - READY TO CONFIRM', 255, 255, 255);

    text(377, 764, 8.5, 'F2', 'Folio Ref: #DRAFT-BK-2026-1094', 252, 211, 77);
    text(377, 751, 7.5, 'F1', 'Issued: 2026-10-14 14:00 BST', 203, 213, 225);

    // Mini QR Code Matrix inside Header (x=516, y=751, 34x34)
    rgbFill(255, 255, 255);
    rectFill(515, 750, 36, 36);
    rgbFill(15, 23, 42);
    rectFill(518, 774, 9, 9);
    rectFill(539, 774, 9, 9);
    rectFill(518, 753, 9, 9);
    rectFill(530, 764, 6, 6);
    rectFill(539, 755, 7, 7);

    // 2. Section 1: Stay Schedule & Check-In / Check-Out Window (y=704)
    rgbFill(239, 246, 255);
    rectFill(36, 702, 523, 18);
    rgbFill(37, 99, 235);
    rectFill(36, 702, 3.5, 18);
    text(46, 708, 8.5, 'F2', '1. STAY SCHEDULE & CHECK-IN / CHECK-OUT WINDOW', 15, 23, 42);
    text(375, 708, 7.5, 'F1', 'Standard Check-In 02:00 PM / Check-Out 12:00 PM', 71, 85, 105);

    // 4 KPI Cards (y=654..695)
    const kpis = [
        ['CHECK-IN DATE & TIME', '2026-10-14  02:00 PM', 'Guaranteed Arrival Slot'],
        ['CHECK-OUT DATE & TIME', '2026-10-16  12:00 PM', 'Standard Noon Departure'],
        ['STAY & OCCUPANCY', '2 Nights  |  3 Guests', '2 Suites Assigned'],
        ['BOOKING CHANNEL / TIER', 'Corporate Direct Desk', 'VIP Priority Allocation'],
    ];
    kpis.forEach((k, i) => {
        const kx = 36 + i * 132;
        rgbFill(248, 250, 252);
        rgbStroke(203, 213, 225);
        rectFillStroke(kx, 654, 127, 42, 0.7);
        text(kx + 8, 684, 6.8, 'F2', k[0], 100, 116, 139);
        text(kx + 8, 671, 9.2, 'F2', k[1], 15, 23, 42);
        text(kx + 8, 660, 7, 'F1', k[2], 37, 99, 235);
    });

    // 3. Section 2: Primary Folio Holder & Accompanying Occupants (KYC) (y=628)
    rgbFill(239, 246, 255);
    rectFill(36, 628, 523, 18);
    rgbFill(37, 99, 235);
    rectFill(36, 628, 3.5, 18);
    text(46, 634, 8.5, 'F2', '2. PRIMARY FOLIO HOLDER & ACCOMPANYING OCCUPANTS (KYC)', 15, 23, 42);
    text(405, 634, 7.5, 'F2', '3 Guests Registered (1 Primary + 2 Co-Guests)', 5, 150, 105);

    // Primary Guest Card (y=588..622)
    rgbFill(240, 253, 244);
    rgbStroke(167, 243, 208);
    rectFillStroke(36, 588, 523, 34, 0.75);
    rgbFill(5, 150, 105);
    rectFill(36, 588, 3.5, 34);
    text(46, 611, 7, 'F2', 'PRIMARY GUEST (MASTER FOLIO HOLDER - GOVT. NID / PASSPORT VERIFIED)', 4, 120, 87);
    text(46, 597, 9, 'F2', 'Tanvir Ahmed Chowdhury   |   +880 1711-948210   |   NID: BD-8894021562   |   tanvir.chowdhury@example.com', 15, 23, 42);

    // Accompanying Guests Table (y=518..580)
    rgbFill(241, 245, 249);
    rgbStroke(203, 213, 225);
    rectFillStroke(36, 562, 523, 18, 0.7);
    text(44, 568, 7.5, 'F2', '#', 51, 65, 85);
    text(66, 568, 7.5, 'F2', 'ROOM', 51, 65, 85);
    text(115, 568, 7.5, 'F2', 'ACCOMPANYING GUEST FULL NAME', 51, 65, 85);
    text(275, 568, 7.5, 'F2', 'MOBILE PHONE', 51, 65, 85);
    text(365, 568, 7.5, 'F2', 'NID / PASSPORT', 51, 65, 85);
    text(450, 568, 7.5, 'F2', 'RELATION', 51, 65, 85);
    text(510, 568, 7.5, 'F2', 'AGE/SEX', 51, 65, 85);

    const coGuests = [
        ['1', '#101', 'Rafiqul Islam Bhuiyan', '+880 1911-458920', 'BD-5521904811', 'Colleague', '35 / M'],
        ['2', '#201', 'Nusrat Jahan', '+880 1819-552104', 'BG-0192837', 'Spouse', '29 / F'],
    ];
    coGuests.forEach((g, idx) => {
        const ry = 544 - idx * 18;
        rgbFill(idx % 2 === 0 ? 255 : 248, idx % 2 === 0 ? 255 : 250, idx % 2 === 0 ? 255 : 252);
        rgbStroke(226, 232, 240);
        rectFillStroke(36, ry, 523, 18, 0.6);
        text(44, ry + 5, 8, 'F1', g[0]);
        text(66, ry + 5, 8, 'F2', g[1], 37, 99, 235);
        text(115, ry + 5, 8, 'F2', g[2]);
        text(275, ry + 5, 8, 'F1', g[3]);
        text(365, ry + 5, 8, 'F1', g[4]);
        text(450, ry + 5, 8, 'F1', g[5]);
        text(510, ry + 5, 8, 'F1', g[6]);
    });

    // 4. Section 3: Assigned Rooms & Nightly Tariff Schedule (y=498)
    rgbFill(239, 246, 255);
    rectFill(36, 498, 523, 18);
    rgbFill(37, 99, 235);
    rectFill(36, 498, 3.5, 18);
    text(46, 504, 8.5, 'F2', '3. ASSIGNED SUITES & NIGHTLY TARIFF SCHEDULE', 15, 23, 42);
    text(365, 504, 7.5, 'F1', '2 Rooms Assigned  |  Buffet Breakfast Included', 71, 85, 105);

    // Rooms Table Header
    rgbFill(241, 245, 249);
    rgbStroke(203, 213, 225);
    rectFillStroke(36, 474, 523, 18, 0.7);
    text(44, 480, 7.5, 'F2', 'SL', 51, 65, 85);
    text(72, 480, 7.5, 'F2', 'ROOM & FLOOR', 51, 65, 85);
    text(175, 480, 7.5, 'F2', 'SUITE CATEGORY & BED CONFIGURATION', 51, 65, 85);
    text(365, 480, 7.5, 'F2', 'NIGHTLY RATE', 51, 65, 85);
    text(442, 480, 7.5, 'F2', 'NIGHTS', 51, 65, 85);
    text(492, 480, 7.5, 'F2', 'LINE TOTAL', 51, 65, 85);

    const rooms = [
        ['1', '#101 (Floor 1)', 'Deluxe Double  |  King Bed  |  City View', 'BDT 4,200', '2', 'BDT 8,400'],
        ['2', '#201 (Floor 2)', 'Heritage Suite  |  Suite  |  Balcony Lounge', 'BDT 6,500', '2', 'BDT 13,000'],
    ];
    rooms.forEach((r, idx) => {
        const ry = 456 - idx * 18;
        rgbFill(idx % 2 === 0 ? 255 : 248, idx % 2 === 0 ? 255 : 250, idx % 2 === 0 ? 255 : 252);
        rgbStroke(226, 232, 240);
        rectFillStroke(36, ry, 523, 18, 0.6);
        text(44, ry + 5, 8, 'F1', r[0]);
        text(72, ry + 5, 8, 'F2', r[1], 37, 99, 235);
        text(175, ry + 5, 8, 'F1', r[2]);
        text(365, ry + 5, 8, 'F1', r[3]);
        text(452, ry + 5, 8, 'F2', r[4]);
        text(492, ry + 5, 8.5, 'F2', r[5]);
    });

    // 5. Section 4: Financial Folio, Advance Deposit Policy & Balance Summary (y=410)
    rgbFill(239, 246, 255);
    rectFill(36, 410, 523, 18);
    rgbFill(37, 99, 235);
    rectFill(36, 410, 3.5, 18);
    text(46, 416, 8.5, 'F2', '4. FINANCIAL FOLIO, ADVANCE DEPOSIT POLICY & BALANCE SUMMARY', 15, 23, 42);
    text(420, 416, 7.5, 'F1', 'Currency: Bangladeshi Taka (BDT)', 71, 85, 105);

    // Left Box: Advance Deposit Policy & Instructions (x=36, y=232, w=268, h=170)
    rgbFill(248, 250, 252);
    rgbStroke(203, 213, 225);
    rectFillStroke(36, 232, 268, 170, 0.75);
    text(46, 388, 8, 'F2', 'ADVANCE DEPOSIT POLICY & COMPLIANCE', 37, 99, 235);
    text(46, 372, 8, 'F1', 'Selected Policy:', 71, 85, 105);
    text(135, 372, 8, 'F2', 'Min 30% of Net Payable', 15, 23, 42);
    text(46, 357, 8, 'F1', 'Minimum Required Advance:', 71, 85, 105);
    text(175, 357, 8.5, 'F2', 'BDT 7,500', 15, 23, 42);
    text(46, 342, 8, 'F1', 'Advance Tendered Now:', 71, 85, 105);
    text(175, 342, 8.5, 'F2', 'BDT 10,000 (Card)', 5, 150, 105);
    text(46, 327, 8, 'F1', 'Policy Verification:', 71, 85, 105);
    text(175, 327, 8, 'F2', 'MINIMUM SATISFIED (OK)', 5, 150, 105);

    rgbStroke(203, 213, 225);
    line(46, 316, 294, 316, 0.5);
    text(46, 303, 7.5, 'F2', 'Front-Desk & Housekeeping Instructions:', 15, 23, 42);
    text(46, 291, 7.5, 'F1', 'VIP corporate arrival at 14:00. Prepare welcome fruit', 51, 65, 85);
    text(46, 280, 7.5, 'F1', 'basket & high-floor quiet room allocation.', 51, 65, 85);

    text(46, 262, 7.5, 'F2', 'Pre-Confirmation Terms & Conditions:', 15, 23, 42);
    text(46, 250, 7, 'F1', 'Govt. NID/Passport mandatory at check-in for all guests.', 100, 116, 139);
    text(46, 240, 7, 'F1', 'Remaining balance of BDT 15,000 is payable at checkout.', 100, 116, 139);

    // Right Box: Financial Totals Box (x=314, y=232, w=245, h=170)
    rgbFill(245, 250, 255);
    rgbStroke(147, 197, 253);
    rectFillStroke(314, 232, 245, 170, 0.85);

    const finRows = [
        ['Combined Nightly Room Tariff', 'BDT 10,700', false],
        ['Room Stay Subtotal (2 Nights)', 'BDT 21,400', false],
        ['Service Charge (10%) + VAT (15%)', 'BDT 5,350', false],
        ['Gross Stay Payable', 'BDT 26,750', false],
        ['Promotional / Corporate Discount', '- BDT 1,750', false],
        ['Net Stay Payable', 'BDT 25,000', true],
        ['Advance Deposit Paid (Card)', 'BDT 10,000', true],
    ];
    finRows.forEach((row, idx) => {
        const fy = 386 - idx * 17;
        if (idx === 5) {
            rgbStroke(147, 197, 253);
            line(324, fy + 11, 549, fy + 11, 0.6);
        }
        text(324, fy, row[2] ? 8.5 : 8, row[2] ? 'F2' : 'F1', row[0], 15, 23, 42);
        text(485, fy, row[2] ? 8.5 : 8, 'F2', row[1], idx === 6 ? 5 : 15, idx === 6 ? 150 : 23, idx === 6 ? 105 : 42);
    });

    // Highlighted Balance Due Bar inside Right Box (y=240..264)
    rgbFill(15, 23, 42);
    rectFill(322, 240, 229, 26);
    text(332, 250, 9, 'F2', 'BALANCE DUE AT CHECKOUT', 255, 255, 255);
    text(476, 250, 10, 'F2', 'BDT 15,000', 252, 211, 77);

    // 6. Authorized Signatures & Official Hotel Stamp (y=120..215)
    rgbStroke(148, 163, 184);
    line(36, 156, 190, 156, 0.8);
    text(36, 144, 8, 'F2', 'Primary Guest Signature', 15, 23, 42);
    text(36, 133, 7, 'F1', 'I agree to the stay dates, KYC & tariff terms', 100, 116, 139);

    // Center Official Stamp Box
    rgbFill(239, 246, 255);
    rgbStroke(37, 99, 235);
    rectFillStroke(218, 142, 158, 38, 0.8);
    text(233, 163, 7.5, 'F2', 'M&M HERITAGE OFFICIAL SEAL', 37, 99, 235);
    text(242, 151, 7, 'F1', 'Verified Pre-Confirmation Draft', 71, 85, 105);

    // Right Officer Signature
    rgbStroke(148, 163, 184);
    line(405, 156, 559, 156, 0.8);
    text(416, 144, 8, 'F2', 'Front-Desk Reservation Officer', 15, 23, 42);
    text(428, 133, 7, 'F1', 'M&M Heritage Hotel & Suites', 100, 116, 139);

    // Footer Bar (y=36..62)
    rgbFill(248, 250, 252);
    rgbStroke(226, 232, 240);
    rectFillStroke(36, 38, 523, 24, 0.6);
    text(46, 47, 7.5, 'F2', 'Document ID: PDF-DRAFT-BK-2026-1094   |   M&M Heritage PMS v2.4', 71, 85, 105);
    text(410, 47, 7.5, 'F1', 'Page 1 of 1  |  A4 Executive Summary', 100, 116, 139);

    return ops.join('\n');
}

function generatePdfBuffer() {
    const stream = buildContentStream();
    const objects = [
        '1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n',
        '2 0 obj\n<< /Type /Pages /Kids [3 0 R] /Count 1 >>\nendobj\n',
        '3 0 obj\n<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Resources << /Font << /F1 4 0 R /F2 5 0 R >> >> /Contents 6 0 R >>\nendobj\n',
        '4 0 obj\n<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>\nendobj\n',
        '5 0 obj\n<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold >>\nendobj\n',
        `6 0 obj\n<< /Length ${Buffer.byteLength(stream, 'utf8')} >>\nstream\n${stream}\nendstream\nendobj\n`,
    ];

    let pdf = '%PDF-1.4\n%\xE2\xE3\xCF\xD3\n';
    const offsets = [0];
    for (const obj of objects) {
        offsets.push(Buffer.byteLength(pdf, 'utf8'));
        pdf += obj;
    }
    const xrefOffset = Buffer.byteLength(pdf, 'utf8');
    pdf += `xref\n0 ${objects.length + 1}\n`;
    pdf += '0000000000 65535 f \n';
    for (let i = 1; i < offsets.length; i++) {
        pdf += String(offsets[i]).padStart(10, '0') + ' 00000 n \n';
    }
    pdf += `trailer\n<< /Size ${objects.length + 1} /Root 1 0 R >>\nstartxref\n${xrefOffset}\n%%EOF\n`;
    return Buffer.from(pdf, 'utf8');
}

const outDir = path.resolve(__dirname, '../public/assets/docs');
fs.mkdirSync(outDir, { recursive: true });
const outFile = path.join(outDir, 'draft-reservation-summary.pdf');
const buf = generatePdfBuffer();
fs.writeFileSync(outFile, buf);
console.log(`Generated ${outFile} (${buf.length} bytes)`);
