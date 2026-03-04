<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>School Administrators Matrix</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
</head>
<body class="bg-gradient-to-br from-amber-50 via-stone-100 to-zinc-200">

<div class="flex min-h-screen">
  @include('components.adminSideBar')

  <main class="flex-1 ml-16 transition-all duration-300">
    <section class="px-4 md:px-8 pt-8 pb-6">
      <div class="rounded-2xl border border-stone-300 bg-white/80 backdrop-blur-sm shadow-sm p-5 md:p-7">
        <div class="flex flex-wrap items-start justify-between gap-4">
          <div>
            <p class="text-xs uppercase tracking-[0.2em] text-stone-600">Matrix 14</p>
            <h1 class="mt-1 text-xl md:text-2xl font-semibold text-stone-900">
              Matrix List of School Administrators
            </h1>
            <p class="mt-1 text-sm text-stone-600">
              President, Vice-President(s), Deans, and Department Heads
            </p>
          </div>
          <button
            type="button"
            onclick="window.print()"
            class="inline-flex items-center gap-2 rounded-lg border border-stone-300 bg-stone-50 px-4 py-2 text-sm font-medium text-stone-700 hover:bg-stone-100"
          >
            <i class="fa-solid fa-print"></i>
            Print
          </button>
        </div>
      </div>
    </section>

    <section class="px-4 md:px-8 pb-10">
      <div class="overflow-x-auto rounded-2xl border border-stone-300 bg-white shadow-sm">
        <table class="min-w-[1300px] w-full text-sm text-stone-800 border-collapse">
          <thead class="bg-stone-100">
            <tr>
              <th class="border border-stone-300 px-3 py-3 text-left font-semibold w-[220px]">Name of Dean/Program Head</th>
              <th class="border border-stone-300 px-3 py-3 text-left font-semibold w-[350px]">Educational Qualifications (school, degree, and when obtained)</th>
              <th class="border border-stone-300 px-3 py-3 text-left font-semibold w-[240px]">Position/Designation</th>
              <th class="border border-stone-300 px-3 py-3 text-left font-semibold w-[140px]">Status of Employment</th>
              <th class="border border-stone-300 px-3 py-3 text-left font-semibold w-[150px]">Rate of Salary per month</th>
              <th class="border border-stone-300 px-3 py-3 text-left font-semibold w-[210px]">Other Employment Benefits</th>
              <th class="border border-stone-300 px-3 py-3 text-left font-semibold w-[320px]">Relevant Experience/s</th>
            </tr>
          </thead>
          <tbody class="align-top">
            <tr class="odd:bg-white even:bg-stone-50/40">
              <td class="border border-stone-300 px-3 py-3 font-medium">TOMAS C. BAUTISTA, PhD.</td>
              <td class="border border-stone-300 px-3 py-3">
                <ul class="list-disc pl-5 space-y-1">
                  <li>Doctor of Philosophy, <span class="italic">Northeastern College</span>, 2000</li>
                  <li>Master in Business Administration, <span class="italic">Northeastern College</span>, 1997</li>
                  <li>Bachelor of Laws, <span class="italic">Far Eastern University</span>, 1986</li>
                  <li>Bachelor of Arts, <span class="italic">Northeastern College</span>, 1975</li>
                </ul>
              </td>
              <td class="border border-stone-300 px-3 py-3">
                <ul class="list-disc pl-5 space-y-1">
                  <li>President</li>
                  <li>Dean, College of Criminology</li>
                </ul>
              </td>
              <td class="border border-stone-300 px-3 py-3">Permanent</td>
              <td class="border border-stone-300 px-3 py-3">56,674.88</td>
              <td class="border border-stone-300 px-3 py-3">13th Month Pay, Cost of Living Allowance (COLA)</td>
              <td class="border border-stone-300 px-3 py-3">
                <ul class="list-disc pl-5 space-y-1">
                  <li>BOD (1995-present)</li>
                  <li>President (1995-present)</li>
                  <li>Principal, Graduate School (2000-present)</li>
                  <li>School Treasurer (1987-1994)</li>
                  <li>School Registrar (1987-1994)</li>
                </ul>
              </td>
            </tr>

            <tr class="odd:bg-white even:bg-stone-50/40">
              <td class="border border-stone-300 px-3 py-3 font-medium">CLEMENTE P. CLARO, JR., PhD CPA</td>
              <td class="border border-stone-300 px-3 py-3">
                <ul class="list-disc pl-5 space-y-1">
                  <li>Doctor of Philosophy, <span class="italic">Northeastern College</span>, 2000</li>
                  <li>Master in Business Administration, <span class="italic">Northeastern College</span>, 1989</li>
                  <li>Bachelor of Science in Commerce, major in Accounting, <span class="italic">Northeastern College</span>, 1981</li>
                </ul>
              </td>
              <td class="border border-stone-300 px-3 py-3">
                <ul class="list-disc pl-5 space-y-1">
                  <li>VP-Academics</li>
                  <li>Dean, College of Accountancy and Business Administration-Management</li>
                  <li>Dean, College of Hospitality Management</li>
                </ul>
              </td>
              <td class="border border-stone-300 px-3 py-3">Permanent</td>
              <td class="border border-stone-300 px-3 py-3">45,972.26</td>
              <td class="border border-stone-300 px-3 py-3">13th Month Pay, Cost of Living Allowance (COLA)</td>
              <td class="border border-stone-300 px-3 py-3">
                <ul class="list-disc pl-5 space-y-1">
                  <li>Dean, College of Accountancy and Business Administration-Management (1990-present)</li>
                  <li>Dean, Hospitality Management (2013-present)</li>
                  <li>Principal, Graduate School (1990-present)</li>
                  <li>Instructor, Undergraduate Program (1982-present)</li>
                </ul>
              </td>
            </tr>

            <tr class="odd:bg-white even:bg-stone-50/40">
              <td class="border border-stone-300 px-3 py-3 font-medium">Sample Administrator</td>
              <td class="border border-stone-300 px-3 py-3">
                <ul class="list-disc pl-5 space-y-1">
                  <li>Doctor of Philosophy, <span class="italic">Sample University</span>, 2004</li>
                  <li>Master in Business Administration, <span class="italic">Sample College</span>, 1994</li>
                </ul>
              </td>
              <td class="border border-stone-300 px-3 py-3">
                <ul class="list-disc pl-5 space-y-1">
                  <li>Dean, Graduate School</li>
                  <li>Vice President, Academic Affairs</li>
                </ul>
              </td>
              <td class="border border-stone-300 px-3 py-3">Permanent</td>
              <td class="border border-stone-300 px-3 py-3">40,000.00</td>
              <td class="border border-stone-300 px-3 py-3">13th Month Pay, COLA</td>
              <td class="border border-stone-300 px-3 py-3">
                <ul class="list-disc pl-5 space-y-1">
                  <li>Dean, Graduate School (1999-present)</li>
                  <li>Chair, Academic Committee (2005-present)</li>
                </ul>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>
  </main>
</div>

</body>
</html>

