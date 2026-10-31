   <section aria-label="Election system summary" class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
       <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
           <div class="flex items-start justify-between">
               <div>
                   <p class="text-sm font-semibold text-slate-500">Total Voters</p>
                   <p class="mt-3 text-3xl font-extrabold tracking-tight text-slate-900">
                       <?php
                        $voters = $pdo->query("SELECT COUNT(*) FROM users WHERE role='voter'")->fetchColumn();
                        echo $voters; ?>
                   </p>
               </div>
               <span
                   class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-blue-700"><svg
                       class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                       stroke-width="1.8" aria-hidden="true">
                       <path stroke-linecap="round"
                           d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2M10 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM20 8v6m3-3h-6" />
                   </svg></span>
           </div>
           <p class="mt-3 text-xs font-medium text-green-700">+8.2% <span
                   class="font-normal text-slate-500">from last month</span></p>
       </article>
       <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
           <div class="flex items-start justify-between">
               <div>
                   <p class="text-sm font-semibold text-slate-500">Total Candidates</p>
                   <p class="mt-3 text-3xl font-extrabold tracking-tight text-slate-900">
                       <?php
                        $candidates = $pdo->query("SELECT COUNT(*) FROM users WHERE role='candidate'")->fetchColumn();
                        echo $candidates; ?>
                   </p>
               </div>
               <span
                   class="flex h-11 w-11 items-center justify-center rounded-xl bg-purple-50 text-purple-700"><svg
                       class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                       stroke-width="1.8" aria-hidden="true">
                       <circle cx="12" cy="8" r="4" />
                       <path stroke-linecap="round" d="M5 21v-2a7 7 0 0 1 14 0v2" />
                   </svg></span>
           </div>
           <p class="mt-3 text-xs font-medium text-slate-500">Across all registered elections</p>
       </article>
       <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
           <div class="flex items-start justify-between">
               <div>
                   <p class="text-sm font-semibold text-slate-500">Pending Applications</p>
                   <p class="mt-3 text-3xl font-extrabold tracking-tight text-slate-900">
                       <?php
                        $pending_applications = $pdo->query("SELECT COUNT(*) FROM applications WHERE status='pending'")->fetchColumn();
                        echo $pending_applications; ?>
                   </p>
               </div>
               <span
                   class="flex h-11 w-11 items-center justify-center rounded-xl bg-orange-50 text-orange-700"><svg
                       class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                       stroke-width="1.8" aria-hidden="true">
                       <circle cx="12" cy="12" r="9" />
                       <path stroke-linecap="round" d="M12 7v5l3 2" />
                   </svg></span>
           </div>
           <p class="mt-3 text-xs font-medium text-orange-700">Needs your review</p>
       </article>
       <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
           <div class="flex items-start justify-between">
               <div>
                   <p class="text-sm font-semibold text-slate-500">Active Elections</p>
                   <p class="mt-3 text-3xl font-extrabold tracking-tight text-slate-900">
                       <?php
                        $active_elections = $pdo->query("SELECT COUNT(*) FROM elections WHERE status!='completed'")->fetchColumn();
                        echo $active_elections; ?>
                   </p>
               </div>
               <span
                   class="flex h-11 w-11 items-center justify-center rounded-xl bg-green-50 text-green-700"><svg
                       class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                       stroke-width="1.8" aria-hidden="true">
                       <path stroke-linecap="round" stroke-linejoin="round" d="m5 12 4 4L19 6" />
                   </svg></span>
           </div>
           <p class="mt-3 text-xs font-medium text-green-700">
               <?php
                $ongoing_elections = $pdo->query("SELECT COUNT(*) FROM elections WHERE status='ongoing'")->fetchColumn();
                echo $ongoing_elections; ?> election currently ongoing</p>
       </article>
   </section>