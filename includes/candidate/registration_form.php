<?php
echo '<div class="mx-auto max-w-5xl">
                <div class="mb-8 flex items-start gap-4 rounded-2xl border border-blue-200 bg-blue-50 p-5">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-xl font-bold text-blue-700">
                        i
                    </div>

                    <div>
                        <h2 class="font-bold text-blue-900">Before you submit</h2>
                        <p class="mt-1 text-sm leading-6 text-blue-800">
                            Complete all required fields accurately. Your application will remain pending until it is reviewed by an administrator.
                            Submitting this form does not guarantee approval.
                        </p>
                        <p class="mt-3 text-sm leading-6 text-blue-800">
                            Need to update your account information? <a href="' . htmlspecialchars(app_url('views/candidate/profile.php'), ENT_QUOTES, 'UTF-8') . '" class="font-semibold text-blue-900 underline decoration-blue-300 underline-offset-2 hover:text-blue-700">Go to My Profile</a>.
                        </p>
                        <p class="mt-2 text-sm leading-6 text-blue-800">
                            <span class="font-semibold">Please note:</span> You cannot edit application details after submission. Contact the election administrator if you need to make changes.
                        </p>

                    </div>
                </div>

                <form action="' . htmlspecialchars(app_url('views/candidate/registration.php'), ENT_QUOTES, 'UTF-8') . '" method="post" id="candidateForm" class="space-y-8">
                    ' . csrfInput() . '

                    
                    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                        <div class="border-b border-slate-100 px-6 py-5 sm:px-8">
                            <div class="flex items-center gap-3">
                                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-purple-100 font-bold text-purple-700">01</span>
                                <div>
                                    <h2 class="text-lg font-bold text-slate-900">Personal Information</h2>
                                    <p class="mt-1 text-sm text-slate-500">Your identity and contact details</p>
                                </div>
                            </div>
                        </div>

                        <div class="grid gap-6 p-6 sm:grid-cols-2 sm:p-8">

                     
                            <div class="sm:col-span-2">
                                <label class="mb-3 block text-sm font-semibold">Candidate Photograph <span class="text-red-500">*</span></label>

                                <div class="flex flex-wrap items-center gap-5">
                                    <div class="flex h-28 w-28 items-center justify-center overflow-hidden rounded-2xl border-2 border-dashed border-purple-200 bg-purple-50">
                                        <img id="photoPreview" alt="Candidate photo preview"
                                            class="hidden h-full w-full object-cover">

                                        <svg id="photoPlaceholder" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 24 24" fill="currentColor"
                                            class="h-12 w-12 text-purple-300">
                                            <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd" />
                                        </svg>
                                    </div>

                                    <div>
                                        <label for="candidatePhoto"
                                            class="inline-block cursor-pointer rounded-lg border border-purple-200 px-4 py-2.5 text-sm font-semibold text-purple-700 hover:bg-purple-50">
                                            Upload Photograph
                                        </label>

                                        <input type="file" id="candidatePhoto" name="candidate_photo"
                                            accept="image/jpeg,image/png,image/webp"
                                             class="sr-only">

                                        <p id="photoName" class="mt-2 text-xs text-slate-500">
                                            JPG, PNG or WebP · Maximum 2 MB
                                        </p>
                                        <p class="mt-1 text-xs text-slate-500">Use a clear, recent photograph of yourself.</p>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label for="fullName" class="mb-2 block text-sm font-semibold">
                                    Full Legal Name <span class="text-red-500">*</span>
                                </label>
                                <div class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-purple-600 focus:ring-4 focus:ring-purple-100">
                                    ' . $data['name'] . '
                                </div>
                                <p class="mt-1 text-xs text-slate-500">Use the name on your identity document.</p>
                            </div>

                            <div>
                                <label for="fatherName" class="mb-2 block text-sm font-semibold">
                                    Fathers / Guardians Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="fatherName" name="guardian_name" required maxlength="100"
                                    placeholder="Enter fathers or guardians name"
                                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-purple-600 focus:ring-4 focus:ring-purple-100">
                            </div>

                            <div>
                                <label for="dob" class="mb-2 block text-sm font-semibold">
                                    Date of Birth <span class="text-red-500">*</span>
                                </label>
                                <div type="date" id="dob" name="date_of_birth"
                                    value=""
                                    required
                                    class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-purple-600 focus:ring-4 focus:ring-purple-100">
                                    ' . $data['dob'] .
                    '
                                </div>
                                <p class="mt-1 text-xs text-slate-500">Eligibility requirements depend on the election rules.</p>
                            </div>

                            <div>
                                <label for="gender" class="mb-2 block text-sm font-semibold">
                                    Gender <span class="text-red-500">*</span>
                                </label>
                                <div class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-purple-600 focus:ring-4 focus:ring-purple-100">
                                    ' . ucfirst($data['gender']) . '
                                </div>
                            </div>

                            <div>
                                <label for="phone" class="mb-2 block text-sm font-semibold">
                                    Contact Number <span class="text-red-500">*</span>
                                </label>
                                <input type="tel" id="phone" name="phone" required maxlength="20"
                                    autocomplete="tel" placeholder="+92 3XX XXXXXXX"
                                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-purple-600 focus:ring-4 focus:ring-purple-100">
                            </div>

                            <div>
                                <label for="email" class="mb-2 block text-sm font-semibold">
                                    Email Address <span class="text-red-500">*</span>
                                </label>
                                <div
                                    class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-purple-600 focus:ring-4 focus:ring-purple-100">
                                    ' . $data['email'] . '
                                </div>
                                <p class="mt-1 text-xs text-slate-500">This should match the email used for your account.</p>
                            </div>

                            <div>
                                <label for="identityType" class="mb-2 block text-sm font-semibold">
                                    Identity Document <span class="text-red-500">*</span>
                                </label>
                                <div
                                    class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-purple-600 focus:ring-4 focus:ring-purple-100">
                                    ' . strtoupper($data['identity_type']) . '
                                </div>
                            </div>

                            <div>
                                <label for="identityNumber" class="mb-2 block text-sm font-semibold">
                                    CNIC / Passport Number <span class="text-red-500">*</span>
                                </label>
                                <div
                                    class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-purple-600 focus:ring-4 focus:ring-purple-100">
                                    ' . $data['identity_number'] . '
                                </div>
                                <p id="identityHelp" class="mt-1 text-xs text-slate-500">
                                    Enter your 13-digit CNIC, with or without hyphens.
                                </p>
                            </div>

                        </div>
                    </section>


                    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                        <div class="border-b border-slate-100 px-6 py-5 sm:px-8">
                            <div class="flex items-center gap-3">
                                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100 font-bold text-blue-700">02</span>
                                <div>
                                    <h2 class="text-lg font-bold text-slate-900">Residential Address</h2>
                                    <p class="mt-1 text-sm text-slate-500">Where you currently reside</p>
                                </div>
                            </div>
                        </div>

                        <div class="grid gap-6 p-6 sm:grid-cols-2 sm:p-8">

                            <div class="sm:col-span-2">
                                <label for="address" class="mb-2 block text-sm font-semibold">
                                    Complete Address <span class="text-red-500">*</span>
                                </label>
                                <textarea id="address" name="address" required rows="3" maxlength="500"
                                    placeholder="House number, street, area, etc."
                                    class="w-full resize-y rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-purple-600 focus:ring-4 focus:ring-purple-100"></textarea>
                            </div>

                            <div>
                                <label for="city" class="mb-2 block text-sm font-semibold">
                                    City <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="city" name="city" required maxlength="100"
                                    placeholder="e.g. Lahore"
                                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-purple-600 focus:ring-4 focus:ring-purple-100">
                            </div>

                            <div>
                                <label for="province" class="mb-2 block text-sm font-semibold">
                                    Province / State <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="province" name="province" required maxlength="100"
                                    placeholder="e.g. Punjab"
                                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-purple-600 focus:ring-4 focus:ring-purple-100">
                            </div>

                            <div>
                                <label for="country" class="mb-2 block text-sm font-semibold">
                                    Country <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="country" name="country" required maxlength="100"
                                    value="Pakistan"
                                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-purple-600 focus:ring-4 focus:ring-purple-100">
                            </div>

                            <div>
                                <label for="postalCode" class="mb-2 block text-sm font-semibold">
                                    Postal Code <span class="text-slate-400">(Optional)</span>
                                </label>
                                <input type="text" id="postalCode" name="postal_code" maxlength="15"
                                    placeholder="Postal code"
                                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-purple-600 focus:ring-4 focus:ring-purple-100">
                            </div>

                        </div>
                    </section>


                    <!-- Section 3: Election Details -->
                    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                        <div class="border-b border-slate-100 px-6 py-5 sm:px-8">
                            <div class="flex items-center gap-3">
                                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-orange-100 font-bold text-orange-700">03</span>
                                <div>
                                    <h2 class="text-lg font-bold text-slate-900">Election & Candidacy Details</h2>
                                    <p class="mt-1 text-sm text-slate-500">Tell us which election you want to contest</p>
                                </div>
                            </div>
                        </div>

                        <div class="grid gap-6 p-6 sm:grid-cols-2 sm:p-8">

                            <div class="sm:col-span-2">
                                <label for="election" class="mb-2 block text-sm font-semibold">
                                    Election <span class="text-red-500">*</span>
                                </label>
                                <select id="election" name="election_id" required
                                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-purple-600 focus:ring-4 focus:ring-purple-100">
                                    <option value="">Select an available election</option>
                                   ';

                foreach ($elections as $election) {
                    echo '<option value="' . htmlspecialchars($election['id']) . '">' . htmlspecialchars($election['title']) . '</option>';
                }
                echo ' 
                            </select>
                                <p class="mt-1 text-xs text-slate-500">
                                    The actual list should be loaded from elections open for candidate applications.
                                </p>
                            </div>

                            <div>
                                <label for="position" class="mb-2 block text-sm font-semibold">
                                    Position / Seat <span class="text-red-500">*</span>
                                </label>
                                <select id="position" name="position" required
                                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-purple-600 focus:ring-4 focus:ring-purple-100">
                                    <option value="">Select Position</option>
                                    ';
                foreach ($positions as $position) {
                    echo '<option value="' . htmlspecialchars($position['id']) . '">' . htmlspecialchars($position['title']) . '</option>';
                }
                echo '
                                </select>
                            </div>

                            <div>
                                <label for="constituency" class="mb-2 block text-sm font-semibold">
                                    Constituency / Area
                                    <span class="text-slate-400">(If applicable)</span>
                                </label>
                                <input type="text" id="constituency" name="constituency" maxlength="150"
                                    placeholder="Enter constituency or area"
                                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-purple-600 focus:ring-4 focus:ring-purple-100">
                            </div>

                            <div>
                                <label for="party" class="mb-2 block text-sm font-semibold">
                                    Party / Group
                                    <span class="text-slate-400">(Optional)</span>
                                </label>
                                <select name="party" id="party" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-purple-600 focus:ring-4 focus:ring-purple-100">
                                    <option value="">Select Party</option>
                                    ';
                foreach ($parties as $party) {
                    echo '<option value="' . htmlspecialchars($party['id']) . '">' . htmlspecialchars($party['name']) . '</option>';
                }
                echo '
                                </select>
                            </div>

                            <div>
                                <label for="qualification" class="mb-2 block text-sm font-semibold">
                                    Highest Qualification <span class="text-red-500">*</span>
                                </label>
                                <select id="qualification" name="qualification" required
                                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-purple-600 focus:ring-4 focus:ring-purple-100">
                                    <option value="">Select qualification</option>
                                    <option>Secondary School</option>
                                    <option>Higher Secondary School</option>
                                    <option>Diploma</option>
                                    <option>Bachelors Degree</option>
                                    <option>Masters Degree</option>
                                    <option>Doctorate</option>
                                    <option>Other</option>
                                </select>
                            </div>

                            <div class="sm:col-span-2">
                                <label for="experience" class="mb-2 block text-sm font-semibold">
                                    Relevant Experience
                                    <span class="text-slate-400">(Optional)</span>
                                </label>
                                <textarea id="experience" name="experience" rows="3" maxlength="2000"
                                    placeholder="Describe any leadership, community service, or relevant experience."
                                    class="w-full resize-y rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-purple-600 focus:ring-4 focus:ring-purple-100"></textarea>
                            </div>

                            <div class="sm:col-span-2">
                                <label for="statement" class="mb-2 block text-sm font-semibold">
                                    Candidate Statement / Manifesto <span class="text-red-500">*</span>
                                </label>
                                <textarea id="statement" name="manifesto" rows="5" required
                                    minlength="30" maxlength="3000"
                                    placeholder="Explain why you are standing for election, your goals, and what you hope to achieve."
                                    class="w-full resize-y rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-purple-600 focus:ring-4 focus:ring-purple-100"></textarea>
                                <div class="mt-2 flex justify-between gap-3 text-xs text-slate-500">
                                    <span>Write a clear statement about your goals.</span>
                                    <span id="statementCount">0 / 3000</span>
                                </div>
                            </div>

                        </div>
                    </section>


                    <!-- Section 4: Supporting Document -->
                    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                        <div class="border-b border-slate-100 px-6 py-5 sm:px-8">
                            <div class="flex items-center gap-3">
                                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-green-100 font-bold text-green-700">04</span>
                                <div>
                                    <h2 class="text-lg font-bold text-slate-900">Supporting Documents</h2>
                                    <p class="mt-1 text-sm text-slate-500">Additional evidence, if required by the election rules</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 sm:p-8">
                            <label for="supportingDocument" class="mb-2 block text-sm font-semibold">
                                Supporting Document
                                <span class="text-slate-400">(Optional)</span>
                            </label>

                            <input type="file" id="supportingDocument" name="supporting_document"
                                accept=".pdf,.jpg,.jpeg,.png"
                                class="block w-full rounded-xl border border-slate-300 text-sm file:mr-4 file:border-0 file:bg-purple-50 file:px-4 file:py-3 file:font-semibold file:text-purple-700 hover:file:bg-purple-100">

                            <p id="documentName" class="mt-2 text-xs text-slate-500">
                                PDF, JPG or PNG · Maximum 5 MB
                            </p>

                            <p class="mt-3 text-sm leading-6 text-slate-500">
                                Upload only documents specifically requested by the election administrator.
                                Do not upload unnecessary sensitive information.
                            </p>
                        </div>
                    </section>


                    <section class="rounded-2xl border border-purple-200 bg-purple-50/60 p-6 sm:p-8">

                        <h2 class="text-lg font-bold text-slate-900">Declaration & Consent</h2>

                        <p class="mt-2 text-sm leading-6 text-slate-600">
                            Please read the following declaration before submitting your application.
                        </p>

                        <div class="mt-5 space-y-4">

                            <label class="flex items-start gap-3">
                                <input type="checkbox" name="information_accurate" required
                                    class="mt-1 h-4 w-4 shrink-0 accent-purple-700">
                                <span class="text-sm leading-6 text-slate-700">
                                    I confirm that the information provided in this application is accurate and complete.
                                </span>
                            </label>

                            <label class="flex items-start gap-3">
                                <input type="checkbox" name="rules_accepted" required
                                    class="mt-1 h-4 w-4 shrink-0 accent-purple-700">
                                <span class="text-sm leading-6 text-slate-700">
                                    I agree to follow the applicable election rules and understand that my application is subject to review and approval.
                                </span>
                            </label>

                            <label class="flex items-start gap-3">
                                <input type="checkbox" name="privacy_consent" required
                                    class="mt-1 h-4 w-4 shrink-0 accent-purple-700">
                                <span class="text-sm leading-6 text-slate-700">
                                    I consent to the processing of the information necessary to review and administer my candidacy, in accordance with the platforms privacy policy.
                                </span>
                            </label>

                        </div>
                    </section>

                    <div id="formMessage" role="status" aria-live="polite"
                        class="hidden rounded-xl p-4 text-sm leading-6"></div>


                    <!-- Actions -->
                    <div class="flex flex-col-reverse justify-between gap-4 sm:flex-row sm:items-center">
                        <a href="' . htmlspecialchars(app_url(), ENT_QUOTES, 'UTF-8') . '"
                            class="inline-flex justify-center rounded-xl border border-slate-300 bg-white px-6 py-3.5 font-semibold text-slate-700 hover:bg-slate-100">
                            Cancel
                        </a>

                        <button type="submit"
                            class="inline-flex justify-center rounded-xl bg-purple-700 px-7 py-3.5 font-bold text-white shadow-lg shadow-purple-200 transition hover:-translate-y-0.5 hover:bg-purple-800 focus:outline-none focus:ring-4 focus:ring-purple-200">
                            Submit Candidate Application →
                        </button>
                    </div>

                    <p class="text-center text-xs leading-6 text-slate-500">
                        Front-end demonstration only. Your application and uploaded documents are not saved or sent to a server.
                    </p>

                </form>
            </div>
            ';