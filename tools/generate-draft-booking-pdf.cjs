#!/usr/bin/env node
/**
 * Generates a modern 2-Page executive A4 vector PDF:
 *   public/assets/docs/draft-reservation-summary.pdf
 * "Whole Booking Preview — Draft Reservation Summary PDF (Page 1 of 2 & Page 2 of 2)"
 */
const fs = require('fs');
const path = require('path');

function esc(str) {
    return String(str)
        .replace(/\\/g, '\\\\')
        .replace(/\(/g, '\\(')
        .replace(/\)/g, '\\)');
}

function makeHelpers(ops) {
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
    return { rgbFill, rgbStroke, rectFill, rectStroke, rectFillStroke, line, text };
}

function buildPage1Stream() {
    const ops = [];
    const { rgbFill, rgbStroke, rectFill, rectStroke, rectFillStroke, text } = makeHelpers(ops);

    // Page Background (White A4: 595 x 842)
    rgbFill(255, 255, 255);
    rectFill(0, 0, 595, 842);
    rgbStroke(203, 213, 225);
    rectStroke(24, 24, 547, 794, 0.9);

    // 1. Redesigned Architectural Luxury Hotel Letterhead (y=726..818)
    rgbFill(250, 251, 253);
    rectFill(24, 726, 547, 92);
    rgbFill(15, 23, 42);
    rectFill(24, 813, 547, 5);
    rgbFill(217, 119, 6);
    rectFill(24, 724, 547, 2);

    // Royal Navy & Gold Framed Crest Box
    rgbFill(15, 23, 42);
    rgbStroke(245, 158, 11);
    rectFillStroke(36, 742, 50, 56, 1.5);
    text(48, 785, 6.5, 'F2', '* * *', 252, 211, 77);
    text(44, 766, 13.5, 'F2', 'M&M', 252, 211, 77);
    text(47, 750, 6, 'F2', 'EST. BD', 147, 197, 253);

    text(96, 789, 8, 'F2', '* * * * *   FIVE-STAR BOUTIQUE HOSPITALITY  |  DHAKA, BANGLADESH', 180, 83, 9);
    text(96, 768, 16, 'F2', 'M&M HERITAGE HOTEL & SUITES', 15, 23, 42);
    text(96, 752, 8.5, 'F1', 'Plot 42, Road 90, Gulshan-2, Dhaka-1212   |   Tel: +880 2-55051920', 71, 85, 105);
    text(96, 739, 8.5, 'F1', 'BIN: 004192837-0101   |   reservations@mmheritagehotel.com', 100, 116, 139);

    // Right Header Document Verification Card
    rgbFill(255, 255, 255);
    rgbStroke(203, 213, 225);
    rectFillStroke(364, 738, 195, 66, 0.85);
    rgbFill(30, 58, 138);
    rectFill(364, 738, 3.5, 66);
    text(374, 792, 7, 'F2', 'DRAFT RESERVATION SUMMARY - PAGE 1/2', 30, 58, 138);

    rgbFill(5, 150, 105);
    rectFill(374, 773, 130, 14);
    text(380, 777, 7.5, 'F2', 'DRAFT - READY TO CONFIRM', 255, 255, 255);
    text(374, 759, 8.5, 'F2', 'Ref: #DRAFT-BK-2026-1094', 29, 78, 216);
    text(374, 746, 7.5, 'F1', 'Issued: 2026-10-14 14:00 BST', 100, 116, 139);

    // Mini QR Code Matrix
    rgbFill(255, 255, 255);
    rgbStroke(203, 213, 225);
    rectFillStroke(515, 747, 36, 36, 0.6);
    rgbFill(15, 23, 42);
    rectFill(518, 771, 9, 9);
    rectFill(539, 771, 9, 9);
    rectFill(518, 750, 9, 9);
    rectFill(530, 761, 6, 6);
    rectFill(539, 752, 7, 7);

    // Section 1: Stay Schedule & Check-In / Check-Out Window (y=690)
    rgbFill(239, 246, 255);
    rectFill(36, 688, 523, 22);
    rgbFill(37, 99, 235);
    rectFill(36, 688, 3.5, 22);
    text(46, 696, 9, 'F2', '1. STAY SCHEDULE & CHECK-IN / CHECK-OUT WINDOW', 15, 23, 42);
    text(365, 696, 8, 'F1', 'Standard Check-In 02:00 PM / Check-Out 12:00 PM', 71, 85, 105);

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
        rectFillStroke(kx, 628, 127, 50, 0.75);
        text(kx + 9, 664, 7, 'F2', k[0], 100, 116, 139);
        text(kx + 9, 649, 9.5, 'F2', k[1], 15, 23, 42);
        text(kx + 9, 636, 7.5, 'F1', k[2], 37, 99, 235);
    });

    // Section 2: Primary Folio Holder & Accompanying Occupants (KYC) (y=592)
    rgbFill(239, 246, 255);
    rectFill(36, 592, 523, 22);
    rgbFill(37, 99, 235);
    rectFill(36, 592, 3.5, 22);
    text(46, 600, 9, 'F2', '2. PRIMARY FOLIO HOLDER & ACCOMPANYING OCCUPANTS (KYC)', 15, 23, 42);
    text(395, 600, 8, 'F2', '3 Guests Registered (1 Primary + 2 Co-Guests)', 5, 150, 105);

    // Primary Guest Card
    rgbFill(240, 253, 244);
    rgbStroke(167, 243, 208);
    rectFillStroke(36, 540, 523, 42, 0.8);
    rgbFill(5, 150, 105);
    rectFill(36, 540, 3.5, 42);
    text(48, 568, 7.5, 'F2', 'PRIMARY GUEST (MASTER FOLIO HOLDER - GOVT. NID / PASSPORT VERIFIED)', 4, 120, 87);
    text(48, 551, 9.2, 'F2', 'Tanvir Ahmed Chowdhury   |   +880 1711-948210   |   NID: BD-8894021562   |   tanvir.chowdhury@example.com', 15, 23, 42);

    // Accompanying Guests Table (100% Fit with Proportional Columns)
    rgbFill(241, 245, 249);
    rgbStroke(203, 213, 225);
    rectFillStroke(36, 506, 523, 24, 0.75);
    text(44, 515, 8, 'F2', '#', 51, 65, 85);
    text(68, 515, 8, 'F2', 'ASSIGNED ROOM', 51, 65, 85);
    text(146, 515, 8, 'F2', 'ACCOMPANYING GUEST FULL NAME', 51, 65, 85);
    text(296, 515, 8, 'F2', 'MOBILE PHONE', 51, 65, 85);
    text(384, 515, 8, 'F2', 'NID / PASSPORT NO.', 51, 65, 85);
    text(468, 515, 8, 'F2', 'RELATION', 51, 65, 85);
    text(516, 515, 8, 'F2', 'AGE/SEX', 51, 65, 85);

    const coGuests = [
        ['1', '#101', 'Rafiqul Islam Bhuiyan', '+880 1911-458920', 'BD-5521904811', 'Colleague', '35 / M'],
        ['2', '#201', 'Nusrat Jahan', '+880 1819-552104', 'BG-0192837', 'Spouse', '29 / F'],
    ];
    coGuests.forEach((g, idx) => {
        const ry = 480 - idx * 26;
        rgbFill(idx % 2 === 0 ? 255 : 248, idx % 2 === 0 ? 255 : 250, idx % 2 === 0 ? 255 : 252);
        rgbStroke(226, 232, 240);
        rectFillStroke(36, ry, 523, 26, 0.65);
        text(44, ry + 9, 8.5, 'F1', g[0]);
        text(68, ry + 9, 8.5, 'F2', g[1], 37, 99, 235);
        text(146, ry + 9, 8.5, 'F2', g[2]);
        text(296, ry + 9, 8.5, 'F1', g[3]);
        text(384, ry + 9, 8.5, 'F1', g[4]);
        text(468, ry + 9, 8.5, 'F1', g[5]);
        text(516, ry + 9, 8.5, 'F1', g[6]);
    });

    // Section 3: Assigned Suites & Nightly Tariff Schedule (y=412)
    rgbFill(239, 246, 255);
    rectFill(36, 412, 523, 22);
    rgbFill(37, 99, 235);
    rectFill(36, 412, 3.5, 22);
    text(46, 420, 9, 'F2', '3. ASSIGNED SUITES & NIGHTLY TARIFF SCHEDULE', 15, 23, 42);
    text(355, 420, 8, 'F1', '2 Rooms Assigned  |  Complimentary Buffet Breakfast', 71, 85, 105);

    rgbFill(241, 245, 249);
    rgbStroke(203, 213, 225);
    rectFillStroke(36, 378, 523, 24, 0.75);
    text(44, 387, 8, 'F2', 'SL', 51, 65, 85);
    text(74, 387, 8, 'F2', 'ROOM & FLOOR', 51, 65, 85);
    text(175, 387, 8, 'F2', 'SUITE CATEGORY & BED CONFIGURATION', 51, 65, 85);
    text(365, 387, 8, 'F2', 'NIGHTLY RATE', 51, 65, 85);
    text(442, 387, 8, 'F2', 'NIGHTS', 51, 65, 85);
    text(492, 387, 8, 'F2', 'LINE TOTAL', 51, 65, 85);

    const rooms = [
        ['1', '#101 (Floor 1)', 'Deluxe Double  |  King Bed  |  City View', 'BDT 4,200', '2', 'BDT 8,400'],
        ['2', '#201 (Floor 2)', 'Heritage Suite  |  Suite  |  Balcony Lounge', 'BDT 6,500', '2', 'BDT 13,000'],
    ];
    rooms.forEach((r, idx) => {
        const ry = 352 - idx * 26;
        rgbFill(idx % 2 === 0 ? 255 : 248, idx % 2 === 0 ? 255 : 250, idx % 2 === 0 ? 255 : 252);
        rgbStroke(226, 232, 240);
        rectFillStroke(36, ry, 523, 26, 0.65);
        text(44, ry + 9, 8.5, 'F1', r[0]);
        text(74, ry + 9, 8.5, 'F2', r[1], 37, 99, 235);
        text(175, ry + 9, 8.5, 'F1', r[2]);
        text(365, ry + 9, 8.5, 'F1', r[3]);
        text(452, ry + 9, 8.5, 'F2', r[4]);
        text(492, ry + 9, 9, 'F2', r[5]);
    });

    // Page 1 Footer Bar
    rgbFill(248, 250, 252);
    rgbStroke(226, 232, 240);
    rectFillStroke(36, 38, 523, 26, 0.65);
    text(46, 48, 8, 'F2', 'Document ID: PDF-DRAFT-BK-2026-1094   |   Continued on Page 2 (Financial Folio & Signatures) ->', 71, 85, 105);
    text(422, 48, 8, 'F2', 'Page 1 of 2  |  Stay & Guest KYC', 30, 58, 138);

    return ops.join('\n');
}

function buildPage2Stream() {
    const ops = [];
    const { rgbFill, rgbStroke, rectFill, rectStroke, rectFillStroke, line, text } = makeHelpers(ops);

    // Page Background (White A4: 595 x 842)
    rgbFill(255, 255, 255);
    rectFill(0, 0, 595, 842);
    rgbStroke(203, 213, 225);
    rectStroke(24, 24, 547, 794, 0.9);

    // Page 2 Continuation Header Banner (y=754..818)
    rgbFill(250, 251, 253);
    rectFill(24, 754, 547, 64);
    rgbFill(15, 23, 42);
    rectFill(24, 813, 547, 5);
    rgbFill(217, 119, 6);
    rectFill(24, 752, 547, 2);

    rgbFill(15, 23, 42);
    rgbStroke(245, 158, 11);
    rectFillStroke(36, 764, 72, 34, 1.2);
    text(45, 777, 10, 'F2', 'M&M HOTEL', 252, 211, 77);

    text(118, 788, 12.5, 'F2', 'M&M HERITAGE HOTEL & SUITES - DRAFT SUMMARY (CONTINUED)', 15, 23, 42);
    text(118, 771, 8.5, 'F1', 'Folio Ref: #DRAFT-BK-2026-1094   |   Primary Guest: Tanvir Ahmed Chowdhury   |   2 Suites (2 Nights)', 71, 85, 105);

    rgbFill(30, 58, 138);
    rectFill(482, 772, 76, 20);
    text(493, 779, 8.5, 'F2', 'PAGE 2 OF 2', 255, 255, 255);

    // Section 4: Financial Folio, Advance Deposit Policy & Balance Summary (y=710)
    rgbFill(239, 246, 255);
    rectFill(36, 710, 523, 22);
    rgbFill(37, 99, 235);
    rectFill(36, 710, 3.5, 22);
    text(46, 718, 9, 'F2', '4. FINANCIAL FOLIO, ADVANCE DEPOSIT POLICY & BALANCE SUMMARY', 15, 23, 42);
    text(415, 718, 8, 'F1', 'Currency: Bangladeshi Taka (BDT)', 71, 85, 105);

    // Left Box: Advance Deposit Policy & Instructions (x=36, y=470, w=268, h=228)
    rgbFill(248, 250, 252);
    rgbStroke(203, 213, 225);
    rectFillStroke(36, 470, 268, 228, 0.8);
    text(48, 678, 9, 'F2', 'ADVANCE DEPOSIT POLICY & COMPLIANCE', 37, 99, 235);
    text(48, 656, 8.5, 'F1', 'Selected Deposit Policy:', 71, 85, 105);
    text(165, 656, 8.5, 'F2', 'Min 30% of Net Payable', 15, 23, 42);
    text(48, 636, 8.5, 'F1', 'Minimum Required Advance:', 71, 85, 105);
    text(185, 636, 9, 'F2', 'BDT 7,500', 15, 23, 42);
    text(48, 616, 8.5, 'F1', 'Advance Tendered Now:', 71, 85, 105);
    text(185, 616, 9, 'F2', 'BDT 10,000 (Card)', 5, 150, 105);
    text(48, 596, 8.5, 'F1', 'Deposit Verification:', 71, 85, 105);
    text(185, 596, 8.5, 'F2', 'MINIMUM SATISFIED (OK)', 5, 150, 105);

    rgbStroke(203, 213, 225);
    line(48, 580, 292, 580, 0.6);
    text(48, 563, 8.5, 'F2', 'Front-Desk & Housekeeping Instructions:', 15, 23, 42);
    text(48, 548, 8, 'F1', 'VIP corporate arrival at 14:00. Prepare welcome fruit', 51, 65, 85);
    text(48, 535, 8, 'F1', 'basket & high-floor quiet room allocation.', 51, 65, 85);

    text(48, 512, 8.5, 'F2', 'Pre-Confirmation Terms & Conditions:', 15, 23, 42);
    text(48, 497, 7.8, 'F1', 'Govt. NID/Passport mandatory at check-in for all guests.', 100, 116, 139);
    text(48, 484, 7.8, 'F1', 'Remaining balance of BDT 15,000 is payable at checkout.', 100, 116, 139);

    // Right Box: Financial Totals Box (x=314, y=470, w=245, h=228)
    rgbFill(245, 250, 255);
    rgbStroke(147, 197, 253);
    rectFillStroke(314, 470, 245, 228, 0.85);

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
        const fy = 674 - idx * 22;
        if (idx === 5) {
            rgbStroke(147, 197, 253);
            line(324, fy + 14, 549, fy + 14, 0.7);
        }
        text(324, fy, row[2] ? 9 : 8.5, row[2] ? 'F2' : 'F1', row[0], 15, 23, 42);
        text(482, fy, row[2] ? 9 : 8.5, 'F2', row[1], idx === 6 ? 5 : 15, idx === 6 ? 150 : 23, idx === 6 ? 105 : 42);
    });

    // Highlighted Balance Due Bar inside Right Box
    rgbFill(15, 23, 42);
    rectFill(322, 482, 229, 34);
    text(332, 495, 9.5, 'F2', 'BALANCE DUE AT CHECKOUT', 255, 255, 255);
    text(472, 495, 11, 'F2', 'BDT 15,000', 252, 211, 77);

    // Section 5: Guest Acceptance Declaration & Authorized Signatures (y=424)
    rgbFill(239, 246, 255);
    rectFill(36, 424, 523, 22);
    rgbFill(37, 99, 235);
    rectFill(36, 424, 3.5, 22);
    text(46, 432, 9, 'F2', '5. GUEST ACCEPTANCE DECLARATION & AUTHORIZED SIGNATURES', 15, 23, 42);

    rgbFill(248, 250, 252);
    rgbStroke(203, 213, 225);
    rectFillStroke(36, 362, 523, 48, 0.75);
    text(48, 391, 8.2, 'F1', 'I/We hereby verify that the guest KYC particulars, suite assignments, stay dates, nightly tariffs, and advance deposit', 51, 65, 85);
    text(48, 376, 8.2, 'F1', 'recorded in this 2-page Draft Reservation Summary are accurate, and authorize M&M Heritage Hotel & Suites to confirm.', 51, 65, 85);

    // Signatures & Official Hotel Stamp (y=220..320)
    rgbStroke(148, 163, 184);
    line(36, 256, 195, 256, 0.9);
    text(36, 242, 8.5, 'F2', 'Primary Guest Signature', 15, 23, 42);
    text(36, 229, 7.5, 'F1', 'Agree to Stay Dates, KYC & Tariff Terms', 100, 116, 139);

    rgbFill(239, 246, 255);
    rgbStroke(37, 99, 235);
    rectFillStroke(215, 238, 165, 44, 0.9);
    text(230, 263, 8, 'F2', 'M&M HERITAGE OFFICIAL SEAL', 37, 99, 235);
    text(240, 249, 7.5, 'F1', 'Verified Pre-Confirmation Draft', 71, 85, 105);

    rgbStroke(148, 163, 184);
    line(400, 256, 559, 256, 0.9);
    text(412, 242, 8.5, 'F2', 'Front-Desk Reservation Officer', 15, 23, 42);
    text(426, 229, 7.5, 'F1', 'M&M Heritage Hotel & Suites', 100, 116, 139);

    // Page 2 Footer Bar
    rgbFill(248, 250, 252);
    rgbStroke(226, 232, 240);
    rectFillStroke(36, 38, 523, 26, 0.65);
    text(46, 48, 8, 'F2', 'Document ID: PDF-DRAFT-BK-2026-1094   |   M&M Heritage PMS v2.4', 71, 85, 105);
    text(392, 48, 8, 'F2', 'Page 2 of 2  |  Financial Folio & Signatures', 30, 58, 138);

    return ops.join('\n');
}

function generatePdfBuffer() {
    const s1 = buildPage1Stream();
    const s2 = buildPage2Stream();
    const objects = [
        '1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n',
        '2 0 obj\n<< /Type /Pages /Kids [3 0 R 7 0 R] /Count 2 >>\nendobj\n',
        '3 0 obj\n<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Resources << /Font << /F1 4 0 R /F2 5 0 R >> >> /Contents 6 0 R >>\nendobj\n',
        '4 0 obj\n<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>\nendobj\n',
        '5 0 obj\n<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold >>\nendobj\n',
        `6 0 obj\n<< /Length ${Buffer.byteLength(s1, 'utf8')} >>\nstream\n${s1}\nendstream\nendobj\n`,
        '7 0 obj\n<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Resources << /Font << /F1 4 0 R /F2 5 0 R >> >> /Contents 8 0 R >>\nendobj\n',
        `8 0 obj\n<< /Length ${Buffer.byteLength(s2, 'utf8')} >>\nstream\n${s2}\nendstream\nendobj\n`,
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
console.log(`Generated 2-page PDF: ${outFile} (${buf.length} bytes)`);
